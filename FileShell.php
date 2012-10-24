<?php
/**
 * Created by JetBrains PhpStorm.
 * User: webonise
 * Date: 23/10/12
 * Time: 12:37 PM
 * To change this template use File | Settings | File Templates.
 */


class FileShell extends AppShell
{
    public function main()
    {

        $this->stripComments();
    }
    public function startup()
    {
        $this->out("Welcome to codechecker");
    }

    public function getFileName($filePaths,$file,$index)
    {

        foreach ($filePaths as $path)
        {
            $tok = strtok($path, "/");

            while ($tok !== false) {

                $tok = strtok("/");
                if (strpos($tok, ".")) {
                    $this->out("<yellowy>[$index]$tok </yellowy>");
                    $file[$index-1]=$tok;
                    $index++;
                }
            }

        }
        $returnarray[0]=$file;
        $returnarray[1]=$index;
        return $returnarray;
    }
    public function stripComments()
    {
        $this->stdout->styles('flashy', array('text' => 'red','blink' => true));
        $this->stdout->styles('greeny', array('text' => 'green', 'blink' => true));
        $this->stdout->styles('yellowy', array('text' => 'yellow', 'blink' => true));
        if (!defined('T_ML_COMMENT')) {
            define('T_ML_COMMENT', T_COMMENT); // please explain why this needed
        } else {
            define('T_DOC_COMMENT', T_ML_COMMENT);
        }

        $controllerPaths = glob(APP . 'Controller/*.php');

        $this->hr();
        $this->out("<flashy>/** List of Controllers */</flashy>");
        $controllerindex = 1;
        $file=array();
        $returnarray=$this->getFileName($controllerPaths,$file,$controllerindex);
        $file=$returnarray[0];
        $controllerindex=$returnarray[1];

        $modelPaths = glob(APP . 'Model/*.php');
        $this->out("<flashy>/** List of Models */</flashy>");
        $modelindex=$controllerindex;
        $returnarray=$this->getFileName($modelPaths,$file,$modelindex);
        $file=$returnarray[0];
        $modelindex=$returnarray[1];

        $this->out("<yellowy>['q'] Quit </yellowy>");
        //pr($file);
        $option = $this->in("Enter option");
        if (in_array($option, range(1, $controllerindex - 1))) {
            $filename = $controllerPaths[$option - 1];
            $fileCheck=$file[$option-1];

        }
        else if (in_array($option, range($controllerindex-1, $modelindex - 1))) {

            $filename = $modelPaths[$option - ($controllerindex)];
            $fileCheck=$file[$option-1];


        }
        elseif($option=='q')
        {
            return;
        }
        else
        {
            $this->out('invalid option');
            return;
        }
       // $filename = $paths[$option - 1];

        $source = file_get_contents($filename);
        $tokens = token_get_all($source);
        //pr($tokens);
        $this->out('File being checked is : '.$fileCheck);
        foreach ($tokens as $token) {

            if (is_string($token)) {

            } else {
                // token array
                //pr($token);
                list($id, $text) = $token;

                switch ($id) {
                    case T_COMMENT:

                    case T_ML_COMMENT: // we've defined this

                    case T_DOC_COMMENT: // and this


                        if (strpos($text, " @todo") || strpos($text, " @comment") || strpos($text, " @refactor")) {
                            $this->hr();

                            $this->out("<greeny> $text </greeny>");

                            $this->hr();

                        }


                        break;

                    default:

                        break;
                }
            }
        }

    }

}

?>