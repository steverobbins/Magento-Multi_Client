<?php

final class MultiClient
{
    const CONFIG_CLASS = 'Multi_Client_Model_Mage_Core_Config';

    /**
     * The etc dir for this CLIENT_CODE
     */
    protected static $_etcDir;

    /**
     * The "local.xml" for this CLIENT_CODE
     */
    protected static $_localXmlFile;

    /**
     * The Multi Client code
     */
    protected static $_clientCode;

    /**
     * Set developer mode if applicable
     */
    public function __construct()
    {
        if (self::isDev()) {
            Mage::setIsDeveloperMode(true);
            ini_set('display_errors', 1);
        }
    }

    /**
     * Get the Multi Client code
     *
     * @return string
     */
    public static function getClientCode()
    {
        if (self::$_clientCode === null) {
            if (!isset($_SERVER['CLIENT_CODE'])) {
                Mage::throwException('CLIENT_CODE code not set');
            }
            self::$_clientCode = $_SERVER['CLIENT_CODE'];
        }
        return self::$_clientCode;
    }

    /**
     * Determine if this environment is non-production
     *
     * @return boolean
     */
    public static function isDev()
    {
        return isset($_SERVER['ENV']) && $_SERVER['ENV'] == 'dev';
    }

    /**
     * Get the run code
     *
     * @return string
     */
    public static function getRunCode()
    {
        return 'default';
    }

    /**
     * Get the run type
     *
     * @return string
     */
    public static function getRunType()
    {
        return 'store';
    }

    /**
     * Get the run options
     *
     * @return array
     */
    public static function getRunOptions($code = false)
    {
        if ($code !== false) {
            self::$_clientCode = $code;
        }
        return array(
            'config_model' => self::CONFIG_CLASS,
            'client_code'  => $code ?: self::getClientCode()
        );
    }

    /**
     * Get the etc dir for this CLIENT_CODE
     *
     * @return string
     */
    public static function getEtcDir()
    {
        if (self::$_etcDir === null) {
            self::$_etcDir = Mage::getConfig()->getOptions()->getEtcDir()
                . DS . 'multiclient' . DS . self::getClientCode();
        }
        return self::$_etcDir;
    }

    /**
     * Get the "local.xml" for this CLIENT_CODE
     *
     * @return string
     */
    public static function getLocalXmlFile()
    {
        if (self::$_localXmlFile === null) {
            self::$_localXmlFile = self::getEtcDir() . DS . 'local.xml';
        }
        return self::$_localXmlFile;
    }
}