<?php 
use PHPUnit\Framework\TestCase;

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class DocToHtmlTest extends TestCase
{

    protected $pdf2htmlex_path;
    protected $libreoffice_path;
    protected $tmp_dir;
    
    protected $converter;

    protected function setUp()
    {
        $ini = parse_ini_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'tests.ini');
        foreach(['pdf2htmlex_path', 'libreoffice_path'] as $key) {
            if (!is_file($ini[$key])) {
                throw new \Exception(sprintf("invalid value for key : %s, file [%s] does not exists.", $key, $ini[$key]));
            }
            $this->{$key} = $ini[$key];
        }
        $this->tmp_dir = $ini['tmp_dir'];
        $c = new \jinowom\doctohtml\DocToHtml;
        $c->pdf2htmlex_path = $this->pdf2htmlex_path;
        $c->libreoffice_path = $this->libreoffice_path;
        $c->tmp_dir = $this->tmp_dir;
        $this->converter = $c;
    }

  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError()
  {
	$var = new \jinowom\doctohtml\DocToHtml;
	$this->assertTrue(is_object($var));
	unset($var);
  }
  
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testConvertWord()
  {
      $dir = __DIR__  . DIRECTORY_SEPARATOR . 'docs';
      glob($dir);
      echo "\n";
      foreach (glob($dir . DIRECTORY_SEPARATOR . "*.{doc,docx}", GLOB_BRACE) as $filename) {
          echo "filename:" . $filename . "\t";
          try {
              $html_path = $this->converter->convert($filename);
          } catch (\Exception $e) {
              echo $e->getMessage();
              $html_path = '';
          }
          echo " => html_path: " . $html_path . "\n";
          $this->assertNotEmpty($html_path);
      }
  }

    public function testConvertXls()
    {
        $dir = __DIR__  . DIRECTORY_SEPARATOR . 'docs';
        glob($dir);
        echo "\n";
        foreach (glob($dir . DIRECTORY_SEPARATOR . "*.{xls,xlsx}", GLOB_BRACE) as $filename) {
            echo "filename:" . $filename . "\t";
            try {
                $html_path = $this->converter->convert($filename);
            } catch (\Exception $e) {
                echo $e->getMessage();
                $html_path = '';
            }
            echo " => html_path: " . $html_path . "\n";
            $this->assertNotEmpty($html_path);
        }
    }

    public function testConvertPdf()
    {
        $dir = __DIR__  . '/docs';
        glob($dir);
        echo "\n";
        foreach (glob($dir . DIRECTORY_SEPARATOR . "*.pdf", GLOB_BRACE) as $filename) {
            echo "filename:" . $filename . "\t";
            try {
                $html_path = $this->converter->convert($filename);
            } catch (\Exception $e) {
                echo $e->getMessage();
                $html_path = '';
            }
            echo " => html_path: " . $html_path . "\n";
            $this->assertNotEmpty($html_path);
        }
    }
}
