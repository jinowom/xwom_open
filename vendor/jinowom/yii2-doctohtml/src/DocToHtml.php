<?php

namespace jinowom\doctohtml;

use yii\base\Component;
use Yii;

class DocToHtml extends Component
{
    public $libreoffice_path = '/usr/bin/soffice';

    public $pdf2htmlex_path = '/usr/bin/pdf2htmlEX';

    public $tmp_dir = '/tmp/doc2html';

    public $exec_timeout = 300;

    private $exitcode = NULL;

    /**
     * 用途：
     * @param $info
     * @return string
     * @author
     */
    public function convert($filename)
    {
        if (!is_dir($this->tmp_dir)) {
            mkdir($this->tmp_dir, 0777);
        }
        $cur_tmp_dir = $this->tmp_dir . DIRECTORY_SEPARATOR . md5($filename);
        @rmdir($cur_tmp_dir);
        exec(sprintf("rm -rf %s", escapeshellarg($cur_tmp_dir)));
        mkdir($cur_tmp_dir, 0777);

        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $savePath = '';
        $output_name = date("YmdHis") . rand(1000, 9999) . "_new.html";
        switch ($ext) {
            case "xlsx":
            case "xls":
                $base = $cur_tmp_dir;
                $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filename);
                $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($filename);
                $savePath = $base . DIRECTORY_SEPARATOR . $output_name; //这里记得将文件名包含进去
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($objPHPExcel);
                $writer->setSheetIndex(0);
                $writer->save($savePath);
                return $savePath;
                break;
            case "doc":
            case "docx":
                $base = $cur_tmp_dir;
                $cmd = $this->libreoffice_path . ' --headless --convert-to html --outdir ' . $base;
                $cmd .= " " . $filename;
                setlocale(LC_ALL, 'en-us.UTF-8');
                $savePath = $base . DIRECTORY_SEPARATOR . str_replace($ext, 'html', basename($filename));
                break;
            case "pdf":
                //pdf2htmlEX [options] <input.pdf> [<output.html>]
                $base = $cur_tmp_dir;
                $cmd = $this->pdf2htmlex_path . " --embed-image 0 --embed-external-font 0 --optimize-text 0 --dest-dir ";
                $cmd .= $base . " " . $filename . " " . $output_name;
                $savePath = $base . DIRECTORY_SEPARATOR . $output_name;
                break;
            default:
                $cmd = '';
                throw new \Exception("extension $ext is not allowed!\n");
                break;
        }
        //$rs = exec($cmd,$output,$ret);
        // if ($ret !== 0) {
        //     $out = trim(implode(', ', $output));
        //     throw new \Exception("exec $cmd failed. ouput: $out\n");
        // }
        try {
            $this->exec_timeout($cmd, $this->exec_timeout, $is_timeout_kill);
        } catch (\Excetpion $e) {
            throw new \Exception("exec $cmd failed." . $e->getMessage());
        }
        return $savePath;
    }

    public function is_running($process)
    {
        $status = proc_get_status($process);

        /**
         * proc_get_status will only pull valid exitcode one
         * time after process has ended, so cache the exitcode
         * if the process is finished and $exitcode is uninitialized
         */
        if ($status['running'] === FALSE && $this->exitcode === NULL)
            $this->exitcode = $status['exitcode'];

        return $status['running'];
    }

    /**
     * Execute a command and return it's output. Either wait until the command exits or the timeout has expired.
     *
     * @param string $cmd Command to execute.
     * @param number $timeout Timeout in seconds.
     * @return string Output of the command.
     * @throws \Exception
     */
    public function exec_timeout($cmd, $timeout, &$is_timeout_kill)
    {
        $this->exitcode = NULL;
        $is_timeout_kill = true;
        // File descriptors passed to the process.
        $descriptors = array(
            0 => array('pipe', 'r'),  // stdin
            1 => array('pipe', 'w'),  // stdout
            2 => array('pipe', 'w')   // stderr
        );

        // Start the process.
        $process = proc_open('exec ' . $cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            throw new \Exception('Could not execute process');
        }

        // Set the stdout stream to none-blocking.
        stream_set_blocking($pipes[1], 0);

        // Turn the timeout into microseconds.
        $timeout = $timeout * 1000000;

        // Output buffer.
        $buffer = '';

        // While we have time to wait.
        while ($timeout > 0) {
            $start = microtime(true);

            // Wait until we have output or the timer expired.
            $read = array($pipes[1]);
            $other = array();
            stream_select($read, $other, $other, 0, $timeout);

            // Get the status of the process.
            // Do this before we read from the stream,
            // this way we can't lose the last bit of output if the process dies between these functions.
//            $status = proc_get_status($process);
            $is_running = $this->is_running($process);

            // Read the contents from the buffer.
            // This function will always return immediately as the stream is none-blocking.
            $buffer .= stream_get_contents($pipes[1]);

            if (!$is_running) {
                // Break from this loop if the process exited before the timeout.
                $is_timeout_kill = false;
                break;
            }

            // Subtract the number of microseconds that we waited.
            $timeout -= (microtime(true) - $start) * 1000000;
        }

        // Check if there were any errors.
        $errors = stream_get_contents($pipes[2]);

        // Kill the process in case the timeout expired and it's still running.
        // If the process already exited this won't do anything.
        proc_terminate($process, 9);

        // Close all streams.
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($process);

        if ($is_timeout_kill) {
            throw new \Exception("exec timeout, max time limit: " . $this->exec_timeout);
        }

        //else normal kill, we should check exitcode
        if (!empty($errors) && $this->exitcode !== 0) {
            throw new \Exception($errors);
        }

        return $buffer;
    }
}