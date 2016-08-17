<?php

class Upload
{
    public $config = array(
        "max_size" => "2050000", // in bytes
        "allowed" => array( // allowed extensions
            'jpg',
            'gif',
            'jpeg',
            'png'),
        "upload_dir" => "/files/", // directory to upload relative root
        "translit" => "true", // translit file name
        "encoding" => "utf-8"); // encoding
    
    public $file = "";
    
    function getExtension()
    {
        return end(explode(".", $this->file['name']));
    }
    
    function getName()
    {
        $pos = strripos($this->file['name'], ".");
        return substr($this->file['name'], 0, $pos);
    }
    
    function ckeckExtension()
    {
        if (in_array($this->getExtension(), $this->config['allowed']))
            return true;
        else
            return false;
    }
    
    function checkSize()
    {
        if (($this->file['size'] == 0) || ($this->file['size'] > $this->config['max_size']))
            return false;
        else
            return true;
    }
    function translit($string)
    {
        $replace = ['а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' =>
            'e', 'ё' => 'yo', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' =>
            's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'i', 'ь' => '', 'э' => 'e', 'ю' =>
            'ju', 'я' => 'ja', ' ' => '-'];
        $string = mb_strtolower($string, $this->config['encoding']);
        $string = strtr($string, $replace);
        return preg_replace('~[^a-z0-9_\-]~', null, $string);
    }
    function doUpload($file)
    {
        $this->file = $file;
        if (!isset($this->file) || ($this->file == "none") || (empty($this->file['name'])))
            return "Вы не выбрали файл";
        if (!$this->checkSize())
            return "Размер файла не соответствует нормам";
        if (!$this->ckeckExtension())
            return "Неверный формат файла";
        if (!is_uploaded_file($this->file["tmp_name"]))
            return "Ошибка на стороне сервера";
        if ($this->config['translit'])
            $filename = $this->translit($this->getName()) . "." . $this->getExtension();
        else
            $filename = $this->file['name'];
        if (!move_uploaded_file($this->file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $this->
            config['upload_dir'] . $filename))
            return "Невозможно переместить файл";
        $full_path = $this->config['upload_dir'] . $filename;
        $message = "Файл загружен";
        $callback = $_REQUEST['CKEditorFuncNum'];
        return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("' .
            $callback . '", "' . $full_path . '", "' . $message . '" );</script>';
    }
}
if (isset($_FILES['upload'])) {
    $upload = new Upload;
    print $upload->doUpload($_FILES['upload']);
}