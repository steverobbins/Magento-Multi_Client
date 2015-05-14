<?php

class Multi_Client_Model_Mage_Core_Config_Options
    extends Mage_Core_Model_Config_Options
{
    /**
     * Initialize default values of the options
     */
    protected function _construct()
    {
        $code    = $this->getClientCode();
        $appRoot = Mage::getRoot();
        $root    = dirname($appRoot);
        $public  = $root . DS . 'public';

        $this->_data['app_dir']     = $root . DS . 'app';
        $this->_data['base_dir']    = $root;
        $this->_data['code_dir']    = $this->_data['app_dir'] . DS . 'code';
        $this->_data['design_dir']  = $this->_data['app_dir'] . DS . 'design';
        $this->_data['etc_dir']     = $this->_data['app_dir'] . DS . 'etc';
        $this->_data['lib_dir']     = $this->_data['app_dir'] . DS . 'lib';
        $this->_data['locale_dir']  = $this->_data['app_dir'] . DS . 'locale';
        $this->_data['media_dir']   = $public . DS . 'media' . DS . $this->getClientCode();
        $this->_data['skin_dir']    = $public . DS . 'skin';
        $this->_data['var_dir']     = $this->getVarDir();
        $this->_data['tmp_dir']     = $this->_data['var_dir'] . DS . 'tmp';
        $this->_data['cache_dir']   = $this->_data['var_dir'] . DS . 'cache';
        $this->_data['log_dir']     = $this->_data['var_dir'] . DS . 'log';
        $this->_data['session_dir'] = $this->_data['var_dir'] . DS . 'session';
        $this->_data['upload_dir']  = $this->_data['media_dir'] . DS . 'upload';
        $this->_data['export_dir']  = $this->_data['var_dir'] . DS . 'export';
    }

    public function getVarDir()
    {
        $dir = isset($this->_data['var_dir']) ? $this->_data['var_dir']
            : $this->_data['base_dir'] . DS . 'var' . DS . $this->getClientCode();
        if (!$this->createDirIfNotExists($dir)) {
            Mage::throwException('Unable to find writable var_dir');
        }
        return $dir;
    }
}
