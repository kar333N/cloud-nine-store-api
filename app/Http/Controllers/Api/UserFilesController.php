<?php

namespace App\Http\Controllers\Api;


use App\UserFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserFilesController extends Controller
{
    private $result = [
        'empty' => 0,
        'data' => []
    ];

    const MAIL_SUBJECT = 'Ссылка на файл';

    /**
     * Получение метаданных файлов по кускам
     * В случае не указания всех параметров, возврашается количество записей в таблице
     * @param null $take
     * @param null $skip
     * @param null $name
     * @param null $data
     * @return array
     */
    public function chunk($take = null, $skip = 0, $name = null, $data = null)
    {
        $userFiles = [];

        switch ($name) {
            case 'hash-user':
                $name = 'hash_user';
                break;
            case 'file-name':
                $name = 'file_name';
                break;
        }

        $count = UserFile::count();

        if ($name && $take) {
            $userFiles = UserFile::where($name, $data)->skip($skip)->take($take)->get();
            $count = UserFile::where($name, $data)->count();
        } elseif (!$name && $take) {
            $userFiles = UserFile::skip($skip)->take($take)->get();
        } elseif (!$name && $take && !$skip) {
            $userFiles = UserFile::take($take)->get();
        }
        if ($userFiles) {
            return ['data' => $userFiles, 'count' => $count];
        } else {
            return ['count' => $count];
        }
    }

    /**
     * Получение метаданных файла по его id
     *
     * @param  string $id
     * @return array
     */
    public function show($id)
    {
        return UserFile::find($id);
    }


    /**
     * Cоздание экземпляра файла, передаем метаданные, в ответе получаем метаданные вместе с id
     *  'hash_file' во входном массиве должен отсутствовать или быть пустым
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        /** @var Request $post */
        if (!$request || !$request->has('file_name') || !$request->input('file_name')) {
            $this->createResultArray(null);
        }

        if (!$request->has('hash_file') || !$request->input('hash_file')) {
            $post = $request->all();
            //создаем hash_file путем хеширования в md5 с добавлением соли
            $post['hash_file'] = md5($request->input('file_name') . rand(1, 100000));
            $row = UserFile::create($post);
            $this->createResultArray(UserFile::find($row->id));
        }

        return $this->result;
    }


    /**
     * Заливка файла по его id
     * @param Request $request
     * @param $id
     * @param $chunkId
     * @return array
     */
    public function upload(Request $request, $id, $chunkId)
    {
        //заливка файла по его id
        $fileInfo = UserFile::find($id);
        if ($fileInfo->hash_user && $fileInfo->hash_file && $fileInfo->number_parts) {
            $result = [];
            $dir = $fileInfo->hash_user;
            $path = $dir . DIRECTORY_SEPARATOR . $fileInfo->hash_file;
            $chunkFilePath = $path . "_$chunkId";
            $content = $request->getContent();
            if ($content) {
                Storage::put($chunkFilePath, $content);
                $files = Storage::files($dir);
                foreach ($files as $file) {
                    $re = '/^' . $fileInfo->hash_user . '\/' . $fileInfo->hash_file . '_([0-9]*)$/iu';
                    preg_match($re, $file, $matches);
                    if (isset($matches[1])) {
                        $result[$matches[1]] = $file;
                    }
                }
                if (count($result) == $fileInfo->number_parts && !Storage::disk()->exists($path)) {
                    $realPath = storage_path('app/data') . DIRECTORY_SEPARATOR . $path;
                    $f = fopen($realPath, "a");
                    $file_list = [];
                    $keys = '';
                    // установка исключительной блокировки на запись
                    if (flock($f, LOCK_EX)) {
                        ftruncate($f, 0); // очищаем файл
                        foreach ($result as $key => $file) {
                            $file_list[] = $file;
                            $keys .= "_$key";
                            $chunkCont = Storage::get($file);
                            fwrite($f, $chunkCont);
                        }
                        fflush($f);
                        flock($f, LOCK_UN); // снятие блокировки
                    }
                    fclose($f);
                    //декодирование из base 64
                    $resDecode = base64_decode(Storage::get($path));
                    file_put_contents($realPath, $resDecode);
                    //удаление лишних файлов
                    $files = Storage::files($dir);
                    foreach ($files as $file) {
                        $re = '/^' . $fileInfo->hash_user . '\/' . $fileInfo->hash_file . '_[0-9]*$/iu';
                        if (preg_match($re, $file)) {
                            Storage::delete($file);
                        }
                    }
                    //обновляем флаг об удачной загрузке файла
                    UserFile::where('id', $fileInfo->id)->update(['file_presence' => 1]);

                    //http://domain.com/uploaded/[hash_user]/[hash_file]
                    //uploaded/{hash_user}/{hash_file}
                    $host = $request->getSchemeAndHttpHost();
                    $url = "$host/uploaded/{$fileInfo->hash_user}/{$fileInfo->hash_file}";
                    $data = [
                        'url' => $url,
                        'title' => 'Файл загружен в облако',
                        'email' => $fileInfo->email,
                        'description' => $fileInfo->description
                    ];
                    Mail::send('emails.notification', ['data' => $data], function ($m) use ($fileInfo) {
                        $m->to($fileInfo->email)->subject(self::MAIL_SUBJECT);
                    });

                    $this->createResultArray(['file' => UserFile::find($id), 'success' => 1]);
                } else {
                    $this->createResultArray(['chunk_id' => $chunkId, 'success' => 1]);
                }
            }
        } else {
            $this->createResultArray(null);
        }

//        header('Access-Control-Allow-Origin: *');
        return $this->result;
    }


    /**
     * Обновление метаданных файла
     * Данные для обновления должны приходить в формате JSON методом PUT
     *
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        if (UserFile::where('id', $id)->update($request->all())) {
            $this->createResultArray(UserFile::find($id));
        } else {
            $this->createResultArray(null);
        }

        return $this->result;
    }

    /**
     * Удаление файла и его метаданных
     *
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        $result = ['delete' => 0];
        $fileInfo = UserFile::find($id);
        if ($fileInfo && $fileInfo->hash_user && $fileInfo->hash_file) {

            $path = $fileInfo->hash_user . DIRECTORY_SEPARATOR . $fileInfo->hash_file;
            if (Storage::exists($path) &&
                Storage::delete($path)
            ) {
                $result['delete'] = 1;
            }

            if (UserFile::find($id)->delete()) {
                $result['delete'] = 1;
            }

        }

        return $result;
    }

    /**
     * Формирование результирующего массива
     * @param $data
     */
    private function createResultArray($data)
    {
        if ($data) {
            $this->result['data'] = $data;
        } else {
            $this->result['empty'] = 1;
        }
    }
}
