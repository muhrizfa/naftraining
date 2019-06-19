<?php
namespace Neuron\Application\Framework\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Primitive extends AbstractActionController {

    const MODULE_INTERNAL = 'internal';
    const MODULE_EXTERNAL = 'external';
    const MODULE_FRONTEND = 'frontend';

    private $_config = array();

    private function extractSubdirToOld($zip, $destination, $subdir){

        $error = new \StdClass();

        $errors = 0;
        $arrErrors = array();
        $arrErrors['folderName'] = array();
        // Prepare dirs
        $destination = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $destination);
        $subdir = str_replace(array("/", "\\"), "/", $subdir);

        if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, "UTF-8") * -1) != DIRECTORY_SEPARATOR)
            $destination .= DIRECTORY_SEPARATOR;

        if (substr($subdir, -1) != "/")
            $subdir .= "/";

        // Extract files
        for ($i = 0; $i < $zip->numFiles; $i++){
            $filename = $zip->getNameIndex($i);
            $pos = strpos($filename, "/src/Neuron/");

            if($pos !== false){
                $subdir = substr($filename, 0, $pos + 12);
                if (substr($filename, 0, mb_strlen($subdir, "UTF-8")) == $subdir){
                    $relativePath = substr($filename, mb_strlen($subdir, "UTF-8"));
                    $relativePath = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $relativePath);

                    if (mb_strlen($relativePath, "UTF-8") > 0){
                        if (substr($filename, -1) == "/"){
                            // New dir
                            if (!is_dir($destination . $relativePath))
                                if (!@mkdir($destination . $relativePath, 0755, true))
                                    $errors++;
                                    $arrErrors[$i] = $filename;
                        } else {
                            if (dirname($relativePath) != ".") {
                                if (!is_dir($destination . dirname($relativePath))){
                                    // New dir (for file)
                                    @mkdir($destination . dirname($relativePath), 0755, true);
                                }
                            }

                            // New file
                            if (@file_put_contents($destination . $relativePath, $zip->getFromIndex($i)) === false)
                                $errors++;
                                $arrErrors[$i] = $filename;
                        }
                    }
                    $ps = strpos($relativePath, DIRECTORY_SEPARATOR);
                    $folder = $ps !== false ? substr($relativePath, 0, $ps) : false;
                    if($folder && !in_array($folder, $arrErrors['folderName'])){
                        array_push($arrErrors['folderName'], $folder);
                    }
                }
            }
        }

        $error->code = $errors;
        $error->data = $arrErrors;

        return $error;
    }


    private function extractSubdirTo($zip, $destination, $subdir, $exclude = null){

        $errors = 0;
        $arrErrors = array();
        $arrErrors['folderName'] = array();
        // Prepare dirs
        $destination = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $destination);
        $subdir = str_replace(array("/", "\\"), "/", $subdir);

        if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, "UTF-8") * -1) != DIRECTORY_SEPARATOR)
            $destination .= DIRECTORY_SEPARATOR;

        if (strlen($subdir) > 0 && substr($subdir, -1) != "/")
            $subdir .= "/";

        // Extract files
        for ($i = 0; $i < $zip->numFiles; $i++){
            $filename = $zip->getNameIndex($i);
            
            $continue = true;
            
            if($exclude && count($exclude) > 0){
                foreach($exclude as $exc){
                    $pso = strpos($filename, $exc);
                    if($pso !== false){
                        $continue = false;
                        break;
                    }
                }
            }
            
            if($continue){
                $start = 0;
                if(substr($filename, 0, mb_strlen($subdir, "UTF-8")) != $subdir){
                    $pos = strpos($filename, '/'.$subdir);
                    if($pos !== false){
                        $subdir = substr($filename, 0, $pos + mb_strlen('/'.$subdir, "UTF-8"));
                        $start = $pos;
                    }
                }
                if (substr($filename, $start, mb_strlen($subdir, "UTF-8")) == $subdir){

                    $relativePath = substr($filename, mb_strlen($subdir, "UTF-8"));
                    $relativePath = str_replace(array("/", "\\"), DIRECTORY_SEPARATOR, $relativePath);

                    if (mb_strlen($relativePath, "UTF-8") > 0){
                        if (substr($filename, -1) == "/"){
                            // New dir
                            if (!is_dir($destination . $relativePath))
                                if (!@mkdir($destination . $relativePath, 0775, true))
                                    $errors++;
                                    $arrErrors[$i] = $filename;
                        } else {
                            if (dirname($relativePath) != ".") {
                                if (!is_dir($destination . dirname($relativePath))){
                                    // New dir (for file)
                                    @mkdir($destination . dirname($relativePath), 0775, true);
                                }
                            }

                            // New file
                            if (@file_put_contents($destination . $relativePath, $zip->getFromIndex($i)) === false)
                                $errors++;
                                $arrErrors[$i] = $filename;
                        }
                    }
                }
            }
        }

        if($errors > 0){
            return false;
        }

        return true;
    }

    private function installInternal($id, $version, $dir){

        set_time_limit(180);

        /* get git config */
        $git = array_key_exists('git', $this->_config) ? $this->_config['git'] : array();
        if (!is_array($git)) $git = array();
        $pac = array_key_exists('pac', $git) ? $git['pac'] : '12345678abcdef';
        $url = array_key_exists('url', $git) ? $git['url'] : 'https://git.neuron.id/api/v4';

        /* get file */
        if($file = @file_get_contents($url . '/projects/'.$id.'/repository/archive.zip?id='.$id.'&sha='.$version.'&private_token='.$pac , false,
            stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                )
            ))
        )){
            /* save file */
            $sha = md5(date('YmdHis'));
            if(!file_exists($dir.'/temp')) mkdir($dir.'/temp', 0775, true);

            if(@file_put_contents($dir.'/temp/'.$sha.'.zip', $file)){
                $zip = new \ZipArchive();
                $zipFile = $dir.'/temp/'.$sha.'.zip';
                if($zip->open($zipFile)){
                    $des = $dir . '/vendor/Neuron';

                    if($this->extractSubdirTo($zip, $des, 'src/Neuron/')){

                        $zip->close();

                    } else {
                        error_log('21114 : extract zip file failed');
                        return false;
                    }
                } else {
                    error_log('21113 : open zip file failed');
                    return false;
                }
                unlink($zipFile);
            } else {
                error_log('21115 : save zip file failed, check \'temp\' folder permissions.');
                return false;
            }
        } else {
            error_log('21112 : download file project failed');
            return false;
        }

        return true;
    }

    private function installExternal($id, $version, $url = null, $config = array(), $dir){

        set_time_limit(600);

        $subdir = array_key_exists('dir', $config) ? $config['dir'] : '';
        $exclude = array_key_exists('exclude', $config) ? $config['exclude'] : null;

        /* get file */
        if($file = @file_get_contents($url, false,
            stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                )
            ))
        )){
            /* save file */
            $sha = md5(date('YmdHis'));
            if(!file_exists($dir.'/temp')) mkdir($dir.'/temp', 0775, true);

            if(@file_put_contents($dir.'/temp/'.$sha.'.zip', $file)){

                $zip = new \ZipArchive();
                $zipFile = $dir.'/temp/'.$sha.'.zip';
                if($zip->open($zipFile)){
                    $des = $dir . '/vendor';

                    if($this->extractSubdirTo($zip, $des . '/'. ucfirst($id), $subdir, $exclude)){

                        $zip->close();

                    } else {
                        error_log('1114 : extract zip file failed');
                        return false;
                    }

                } else {
                    error_log('1113 : open zip file failed');
                    return false;
                }
                unlink($zipFile);
            } else {
                error_log('1115 : save zip file failed, check \'temp\' folder permissions.');
                return false;
            }
        } else {
            error_log('1112 : download file project failed');
            return false;
        }

        return true;
    }

    private function installFrontend($code, $url, $version, $dir, $config = array()){

        set_time_limit(300);

        $runDir = $dir;
        $librariesPath = $runDir.'/public/libraries';
        $libPath = $librariesPath.'/'.$code;
        $subdir = array_key_exists('dir', $config) ? $config['dir'] : '';
        $exclude = array_key_exists('exclude', $config) ? $config['exclude'] : null;

        try {
            /* check temp folder exist */
            if(!file_exists($runDir.'/temp')){
                mkdir($runDir . '/temp', 0775, true);
            }
            /* get file */
            if($file = @file_get_contents($url, false,
                stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    )
                ))
            )){
                /* save file */
                if(@file_put_contents($runDir.'/temp/'.$code.'.zip', $file)){
                    /* extract zip file */
                    $zip = new \ZipArchive();
                    $zipFile = $runDir.'/temp/'.$code.'.zip';
                    if($zip->open($zipFile)){

                        if(!file_exists($libPath)) mkdir($libPath, 0775, true);

                        if($this->extractSubdirTo($zip, $libPath, $subdir, $exclude)){

                            $zip->close();

                        } else {
                            error_log('1105 : failed to extract zip file');
                            return false;
                        }

                    } else {
                        error_log('1104 : failed to open zip file');
                        return false;
                    }
                    /* Delete tmp file */
                    unlink($zipFile);
                } else {
                    error_log('1103 : failed to save zip file');
                    return false;
                }
            } else {
                error_log('1102 : failed to download zip file');
                return false;
            }

        } catch (\Exception $e) {
            error_log('1101 : '.$e->getMessage());
            return false;
        }

        return true;
    }

    private function install($type, $id, $version, $url = null, $config = array(), $dir){

        if($type == self::MODULE_INTERNAL){
            return $this->installInternal($id, $version, $dir);
        } else if($type == self::MODULE_EXTERNAL){
            return $this->installExternal($id, $version, $url, $config, $dir);
        } else if($type == self::MODULE_FRONTEND){
            return $this->installFrontend($id, $url, $version, $dir, $config);
        }

        return false;
    }

    public function onDispatch(\Zend\Mvc\MvcEvent $e) {

        /* set php error to display all & get config */
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->_config = $this->getServiceLocator()->get('Config');

        /* set layout */
        $this->layout()->setTemplate('layout/primitive');

        /* go dispatch! */
        return parent::onDispatch($e);
    }

    public function indexAction() {

        $list = array();
        $last = array();

        /* preformat module order */
        if (array_key_exists('modules', $this->_config)) {

            /* get modules */
            $temp = $this->_config['modules'];

            /* has internal key >= 2.2.0b */
            if (array_key_exists('internal', $temp)) {  //new version is under internal key

                /* storre intenals */
                $modules = $temp['internal'];

                /* add externals */
                if (array_key_exists('external', $temp)) {
                    $external = $temp['external'];
                    foreach ($external as $name => $module) {
                        $list[self::MODULE_EXTERNAL . ':' . $name] = $module;
                    }
                }

                /* add frontends */
                if (array_key_exists('frontend', $temp)) {
                    $frontend = $temp['frontend'];
                    foreach ($frontend as $name => $module) {
                        $list[self::MODULE_FRONTEND . ':' . $name] = $module;
                    }
                }

            } else {

                /* old version --> internal only */
                $modules = $temp;

            }

            /* has internal modules? */
            if (is_array($modules)) {

                /* loop modules */
                foreach ($modules as $name => $module) {
                    switch ($name) {
                        case 'zend': //ignore zend
                          break;
                        case 'core': //put core to last
                          $last = $module;
                          break;
                        default: //add anything else to list
                          $list[self::MODULE_INTERNAL . ':' . $name] = $module;
                    }
                }
                $list[self::MODULE_INTERNAL . ':core'] = $last;  //append last to list (core is last)

            }
        }

        /* send preformatted modules */
        $this->layout()->setVariables(array(
            'modules' => $list,
        ));

        /* set view */
        $view = new ViewModel();

        return $view;
    }

    public function installAction() {

        /* get req res */
        $request = $this->getRequest();
        $response = $this->getResponse();

        /* init result */
        $result = new \StdClass();
        $result->code = 0;
        $result->info = 'OK';
        $result->module = $request->getPost('module');

        /* get temp dir */
        $dir = __DIR__;
        $sep = strstr($dir, '\\') ? '\\' : '/';
        $pos = strpos($dir, "{$sep}application");
        if ($pos !== false) {
            $dir = substr($dir, 0, $pos);
            $temp = $dir . "{$sep}temp";
            $vendor = $dir . "{$sep}vendor{$sep}Neuron";
        } else {
            $temp = $dir . "{$sep}temp";
            $vendor = $dir . "{$sep}vendor{$sep}Neuron";
        }

        /* get git config * /
        $git = array_key_exists('git', $this->_config) ? $this->_config['git'] : array();
        if (!is_array($git)) $git = array();
        $pac = array_key_exists('pac', $git) ? $git['pac'] : '12345678abcdef';
        $url = array_key_exists('url', $git) ? $git['url'] : 'https://git.neuron.id/api/v4';
        */

        /* get module * /
        $module = $result->module;
        $modules = array_key_exists('modules', $this->_config) ? $this->_config['modules'] : array();
        if (array_key_exists('internal', $modules)) {

            if(is_array($modules['internal'])){
                foreach($modules['internal'] as $k => $internal){
                    $modules['internal'][$k]['type'] = self::MODULE_INTERNAL;
                }
            }

            $allModules = $modules['internal'];

            if (array_key_exists('external', $modules)) {
                if(is_array($modules['external'])){
                    foreach($modules['external'] as $k => $external){
                        $modules['external'][$k]['type'] = self::MODULE_EXTERNAL;
                    }
                }
                $allModules = array_merge_recursive($allModules, $modules['external']);
            }
            if (array_key_exists('frontend', $modules)) {
                if(is_array($modules['frontend'])){
                    foreach($modules['frontend'] as $k => $frontend){
                        $modules['frontend'][$k]['type'] = self::MODULE_FRONTEND;
                    }
                }
                $allModules = array_merge_recursive($allModules, $modules['frontend']);
            }
            $modules = $allModules;
        }
        */

        /* get module */
        $module = $result->module;
        $modules = array_key_exists('modules', $this->_config) ? $this->_config['modules'] : array();
        $pos = strpos($module, ':');
        if ($pos !== false) {

            /* new version >= 2.2.0b */
            $group = substr($module, 0, $pos);
            $module = substr($module, $pos + 1);

            /* get modules under group (internal / external / frontend */
            if (array_key_exists($group, $modules)) {
                $modules = $modules[$group];
            }

        } else {

            /* old version always internal module */
            $group = 'internal';

        }

        /* check module config */
        if (is_array($modules) && array_key_exists($module, $modules)) {

            /* get module config */
            $module = is_array($modules[$module]) ? $modules[$module] : array();
            $id = array_key_exists('id', $module) ? $module['id'] : null;
            $name = array_key_exists('name', $module) ? $module['name'] : null;
            $version = array_key_exists('version', $module) ? $module['version'] : null;
            $type = $group;
            $url = array_key_exists('url', $module) ? $module['url'] : null;
            $config = array_key_exists('config', $module) && $module['config'] !== null ? $module['config'] : array();

            /* data ok? */
            if ($id) {

                /* get module archive from git */
                if ($this->install($type, $id, $version, $url, $config, $dir)) {



                } else {

                    /* fail download */
                    $result->code = 3;
                    $result->info = 'Failed when downloading module';

                }

            } else {

                /* fail no version */
                $result->code = 2;
                $result->info = 'Invalid module configuration';

            }

        } else {

            /* fail no module config */
            $result->code = 1;
            $result->info = 'Required module not available on module.php';

        }

        /* set response */
        $headers = new \Zend\Http\Headers();
        $headers->addHeaders(array(
            'Content-Type' => 'application/json',
        ));
        $response->setHeaders($headers);
        $response->setContent(json_encode($result));
        return $response;
    }

}
