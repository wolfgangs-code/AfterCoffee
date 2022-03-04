<?php
class PluginManager
{
    private $settingsArr = [];

    public function __construct()
    {
        foreach (glob(__DIR__ . "/../plugins/*.php") as $plugin) {
            include $plugin;
            $pluginClasses[] = basename($plugin, ".php");
        }
        define("AC_PLUGINS", $pluginClasses);
    }

    public function load($act, $html = null)
    {
        foreach (AC_PLUGINS as $class) {
            $plugin = new $class;
            if (method_exists($plugin, $act)) {
                switch ($act) {
                    case "editorGuide":
                        echo "<li>{$plugin->editorGuide()}</li>";
                        break;
                    case "addSetting":
                        foreach ($plugin->addSetting() as $set) {
                            $settingsArr[get_class($plugin)][$set] = "";
                        }
                        break;
                    case "widgetPage":
                        if (!isset($html)) {
                            $widgetPlugins[] = $class;
                        }
                        if ($class == $html) {
                            return call_user_func([$plugin, $act]);
                        } else {
                            break;
                        }
                        break;
                    case "action":
                        call_user_func([$plugin, $act], $_GET["action"]);
						print($_GET["action"]);
                        break;
                    default:
                        call_user_func([$plugin, $act], $html);
                }
            }
        }
    }

    public function settings()
    {
        $this->load("addSetting");
        return $this->settingsArr;
    }
}
