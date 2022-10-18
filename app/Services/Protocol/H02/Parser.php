<?php declare(strict_types=1);

namespace App\Services\Protocol\H02;

class Parser
{
    /**
     * @var bool
     */
    protected bool $bodyIsValid;

    /**
     * @var array
     */
    protected array $values;

    /**
     * @var array
     */
    protected array $cache = [];

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param string $body
     *
     * @return self
     */
    public function __construct(protected readonly string $body)
    {
    }

    /**
     * @return ?array
     */
    public function data(): ?array
    {
        if ($this->bodyIsValid() === false) {
            return null;
        }

        $this->values = explode(',', substr($this->body, 1, -1));

        return [
            'maker' => $this->maker(),
            'serial' => $this->serial(),
            'type' => $this->type(),
            'latitude' => $this->latitude(),
            'longitude' => $this->longitude(),
            'speed' => $this->speed(),
            'signal' => $this->signal(),
            'direction' => $this->direction(),
            'datetime' => $this->datetime(),
            'country' => $this->country(),
            'timezone' => $this->timezone(),
            'response' => $this->response(),
        ];
    }

    /**
     * @return bool
     */
    public function bodyIsValid(): bool
    {
        return (bool)preg_match($this->bodyIsValidRegExp(), $this->body);
    }

    /**
     * @return string
     */
    protected function bodyIsValidRegExp(): string
    {
        return '/^'
            .'\*[A-Z]{2},'         //  0 - maker
            .'[0-9]+,'             //  1 - serial
            .'[A-Z]+[0-9]+,'       //  2 - type
            .'[0-9]{6},'           //  3 - time
            .'[VA],'               //  4 - signal
            .'[0-9]{4}\.[0-9]{4},' //  5 - latitude
            .'[NS],'               //  6 - latitude direction
            .'[0-9]{5}\.[0-9]{4},' //  7 - longitude
            .'[EW],'               //  8 - longitude direction
            .'[0-9]{3}\.[0-9]{2},' //  9 - speed
            .'[0-9]{3},'           // 10 - direction
            .'[0-9]{6},'           // 11 - date
            .'[0-9A-F]{8},'        // 12 - status
            .'[0-9]+,'             // 13 - mcc
            .'/';
    }

    /**
     * @return string
     */
    protected function maker(): string
    {
        return $this->values[0];
    }

    /**
     * @return string
     */
    protected function serial(): string
    {
        return $this->values[1];
    }

    /**
     * @return string
     */
    protected function type(): string
    {
        return $this->values[2];
    }

    /**
     * @return float
     */
    protected function latitude(): float
    {
        if (isset($this->cache[__FUNCTION__])) {
            return $this->cache[__FUNCTION__];
        }

        $degree = (int)substr($this->values[5], 0, 2);
        $minute = (float)substr($this->values[5], 2, 7);
        $direction = (float)str_replace(['S', 'N'], ['-1', '1'], $this->values[6]);

        return $this->cache[__FUNCTION__] = round(($degree + ($minute / 60)) * $direction, 5);
    }

    /**
     * @return float
     */
    protected function longitude(): float
    {
        if (isset($this->cache[__FUNCTION__])) {
            return $this->cache[__FUNCTION__];
        }

        $degree = (int)substr($this->values[7], 0, 3);
        $minute = (float)substr($this->values[7], 3, 7);
        $direction = (float)str_replace(['W', 'E'], ['-1', '1'], $this->values[8]);

        return $this->cache[__FUNCTION__] = round(($degree + ($minute / 60)) * $direction, 5);
    }

    /**
     * @return float
     */
    protected function speed(): float
    {
        return round((float)$this->values[9] * 1.852, 2);
    }

    /**
     * @return int
     */
    protected function signal(): int
    {
        return $this->values[4] === 'A' ? 1 : 0;
    }

    /**
     * @return int
     */
    protected function direction(): int
    {
        return (int)$this->values[10];
    }

    /**
     * @return ?string
     */
    protected function datetime(): ?string
    {
        $value = $this->values[11].$this->values[3];

        if (preg_match('/^0+$/', $value)) {
            return null;
        }

        $date = str_split($value, 2);

        return '20'.$date[2].'-'.$date[1].'-'.$date[0].' '.$date[3].':'.$date[4].':'.$date[5];
    }

    /**
     * @return ?string
     */
    protected function country(): ?string
    {
        return $this->cache[__FUNCTION__] ??= helper()->mcc((int)$this->values[13])?->iso;
    }

    /**
     * @return ?string
     */
    protected function timezone(): ?string
    {
        return $this->cache[__FUNCTION__] ??= helper()->latitudeLongitudeTimezone($this->latitude(), $this->longitude(), $this->country());
    }

    /**
     * @return string
     */
    protected function response(): string
    {
        return '*'.$this->maker().','.$this->serial().',V4,'.$this->type().','.date('YmdHis').'#';
    }
}
