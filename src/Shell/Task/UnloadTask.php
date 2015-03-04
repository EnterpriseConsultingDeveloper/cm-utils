<?php

namespace Utils\Shell\Task;

use Cake\Console\Shell;
use Cake\Core\App;
use Cake\Filesystem\File;

class UnloadTask extends Shell
{

    public $path = null;
    public $bootstrap = null;

    /**
     * main() method.
     *
     */
    public function main($plugin = null)
    {

        $this->path = current(App::path('Plugin'));
        $this->bootstrap = ROOT . DS . 'config' . DS . 'bootstrap.php';

        if (empty($plugin)) {
            $this->err('<error>You must provide a plugin name in CamelCase format.</error>');
            $this->err('To unload an "Example" plugin, run <info>`cake plugin unload Example`</info>.');
            return false;
        }


        $this->_modifyBootstrap($plugin);
    }

    /**
     * Update the app's bootstrap.php file.
     *
     * @param string $plugin Name of plugin
     * @param bool $hasAutoloader Whether or not there is an autoloader configured for
     * the plugin
     * @return void
     */
    protected function _modifyBootstrap($plugin)
    {
        $finder = "Plugin::load('" . $plugin . "',";

        debug($finder);

        $bootstrap = new File($this->bootstrap, false);
        $contents = $bootstrap->read();
        if (!preg_match("@\n\s*Plugin::loadAll@", $contents)) {

            $_contents = explode("\n", $contents);

            foreach ($_contents as $content) {
                if (strpos($content, $finder) !== false) {
                    $loadString = $content;
                }
            }

            $loadString .= "\n";

            debug($loadString);

            $bootstrap->replaceText(sprintf($loadString), "");

            $this->out('');
            $this->out(sprintf('%s modified', $this->bootstrap));
        }
    }

    /**
     * GetOptionParser method.
     *
     * @return type
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        return $parser;
    }

}
