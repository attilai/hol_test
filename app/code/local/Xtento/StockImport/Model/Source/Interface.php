<?php

/**
 * Product:       Xtento_StockImport (2.1.5)
 * ID:            cIXIdZ5E8uNO9ltV9qw9FYc03wnFMytXL2xZanOHjQk=
 * Packaged:      2014-05-28T11:25:32+00:00
 * Last Modified: 2013-08-07T16:53:10+02:00
 * File:          app/code/local/Xtento/StockImport/Model/Source/Interface.php
 * Copyright:     Copyright (c) 2014 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

interface Xtento_StockImport_Model_Source_Interface
{
    public function testConnection();
    public function loadFiles();
    public function archiveFiles($filesToProcess, $forceDelete = false);
}