<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 12/11/16
 * Time: 18:11
 */

namespace AppBundle\Service;

use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Process\Process;

class PiCamera
{
    use LoggerAwareTrait;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $outputDir;

    /**
     * PiCamera constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options['defaults'];
        $this->outputDir = $options['output_dir'];
    }

    /**
     * @return bool
     */
    public function selfie()
    {
        $now = new \DateTimeImmutable();

        $options = [];
        $options[] = sprintf('--output %s/pic-%d.jpg', $this->outputDir, $now->getTimestamp()); // filename
        foreach ($this->options as $key => $data) {
            $options[] = sprintf('%s %s', $data['command'], $data['default']); // params
        }
        $options[] = '--encoding jpg'; // jpeg encoding
        $options[] = '--vstab'; // stabilization
        $options[] = '--preview 0,0,1296,976'; // preview
        $options[] = sprintf('--exif date="%s"', $now->format('Y-m-d H:i:s')); // put the date in exif
        $options[] = '-v'; // verbose

        $command = sprintf('raspistill %s', implode(' ', $options));

        return $this->process($command);
    }

    /**
     * @return bool
     */
    public function timelaps()
    {
        $now = new \DateTimeImmutable();

        $options = [];
        $options[] = sprintf('--output %s/pic-%d.jpg', $this->outputDir, $now->getTimestamp()); // filename

        $command = sprintf('raspistill %s', implode(' ', $options));

        return $this->process($command);
    }

    /**
     * @param string $command
     * @return bool
     */
    private function process($command)
    {
        $return = true;

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $return = false;
            $this->logger->error($process->getErrorOutput());
        } else {
            $this->logger->info($process->getOutput());
        }

        return $return;
    }
}