<?php

// compute app kernel prod container
$sEnv =  utils::GetCurrentEnvironment();
$sAppKernelProdContainer = APPROOT . "/data/cache-$sEnv/symfony/App_KernelProdContainer.preload.php";

if (file_exists($sAppKernelProdContainer)) {
    require $sAppKernelProdContainer;
}
