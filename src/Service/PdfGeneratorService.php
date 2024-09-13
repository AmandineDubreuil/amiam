<?php

namespace App\Service;

use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;

class PdfGeneratorService
{
    public function __construct(
        private readonly DompdfFactoryInterface $factory,
        private readonly DompdfWrapperInterface $wrapper
    ) {}

public function getPdf(string $html):string {
    return $this->wrapper->getPdf($html);
}

    public function output(string $html): string
    {
        $dompdf = $this->factory->create();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}
