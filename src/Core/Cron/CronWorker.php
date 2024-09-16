<?php
declare(strict_types=1);

namespace Core\Cron;

use Exception;
use Core\Config\ConfigException;
use Core\Logger\Factory;
use Core\Parser\ParserException;
use TypeError;

final class CronWorker extends AbstractCronWorker
{
    private bool $running = true;

    public function run():void
    {
        $i = 0;
        while ($this->running) {
            $i++;
            try {
                $this->setEveryMinuteRun(true);
                $this->notify();
                if ($i >= 60) {
                    $i = 0;
                    $this->setEveryMinuteRun(false);
                    $this->notify();
                }
                sleep($this->sleepTime - date('s') % $this->sleepTime);
            } catch (ParserException $e) {
                Factory::log($e->getMessage());
            } catch (ConfigException $f) {
                Factory::log($f->getMessage());
            } catch (Exception $g) {
                Factory::log($g->getMessage());
            } catch (TypeError $typeError) {
                Factory::log('TypeError: '.$typeError->getMessage());
            }
        }
    }
}
