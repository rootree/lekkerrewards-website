<?php
namespace Application\Service;

class Store
{
    const TOP_LEVEL = 9;

    const TYPE_MERCHANT_LOGO = 'merchant-logo';

    public function __construct($storeConfig)
    {
        $this->storeConfig = $storeConfig;
    }

    /**
     * @param $fileID
     * @param $type
     *
     * @return string
     */
    public function getURL($fileID, $type)
    {
        $fullURL = sprintf('%s%s',
            $this->storeConfig['url-static'],
            $this->getSubPath($type)
        );

        return $this->get($fileID, $fullURL, true).$this->getExtension($type);
    }

    /**
     * @param $fileID
     * @param $type
     *
     * @return string
     */
    public function getPath($fileID, $type)
    {
        $fullPath = sprintf('%s%s',
            $this->storeConfig['path'],
            $this->getSubPath($type)
        );
        return $this->get($fileID, $fullPath).$this->getExtension($type);
    }

    private function getSubPath($type)
    {
        if (!empty($this->storeConfig['type'][$type])) {
            return $this->storeConfig['type'][$type];
        }
        throw new \Exception(sprintf('Unknown path type "%s"', $type));
    }

    public function getExtension($type)
    {
        switch ($type) {
            case self::TYPE_MERCHANT_LOGO:
                return '.jpg';
        }
        throw new \Exception(sprintf('Unknown Extension type "%s"', $type));
    }

    /**
     *
     * Return a path to ID which is divided per dirs (if a dir was not created, this function will create one)
     *
     * @param string $fileID ID файла
     * @param string $serverPath
     * @param bool $isURL
     *
     * @throws \Exception
     * @return string
     */
    public function get($fileID, $serverPath, $isURL = false)
    {
        $id = (int) $fileID;
        if(strlen($id) > self::TOP_LEVEL || $id == 0){
            return null;
        }
        $imgPath = str_pad($id, self::TOP_LEVEL, '0', STR_PAD_LEFT);
        do {
            $subPath = substr($imgPath, 0, 2);
            $imgPath = substr($imgPath, 2);
            $serverPath .= DIRECTORY_SEPARATOR . $subPath;
            // Если каталога нет, создаём его
            //
            if(!is_dir($serverPath) && !$isURL){
                if (!@mkdir($serverPath)) {
                    throw new \Exception(sprintf('Can not make path "%s"', $serverPath));
                };
            }
        } while (strlen($imgPath) > 3);
        return $serverPath . DIRECTORY_SEPARATOR . $fileID;
    }
}