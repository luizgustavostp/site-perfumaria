<?php

class RateLimiter
{
    private string $storageDir;
    private int $maxAttempts;
    private int $decaySeconds;

    public function __construct(int $maxAttempts = 10, int $decaySeconds = 60)
    {
        $this->storageDir  = sys_get_temp_dir() . '/rate_limiter/';
        $this->maxAttempts = $maxAttempts;
        $this->decaySeconds = $decaySeconds;

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    private function getFilePath(string $key): string
    {
        return $this->storageDir . md5($key) . '.json';
    }

    private function getData(string $key): array
    {
        $file = $this->getFilePath($key);

        if (!file_exists($file)) {
            return ['attempts' => 0, 'reset_at' => time() + $this->decaySeconds];
        }

        return json_decode(file_get_contents($file), true);
    }

    private function saveData(string $key, array $data): void
    {
        file_put_contents($this->getFilePath($key), json_encode($data));
    }

    public function attempt(string $key): bool
    {
        $data = $this->getData($key);

        // Se o tempo de reset já passou, zera o contador
        if (time() > $data['reset_at']) {
            $data = ['attempts' => 0, 'reset_at' => time() + $this->decaySeconds];
        }

        // Verifica se já passou do limite
        if ($data['attempts'] >= $this->maxAttempts) {
            return false; // bloqueado
        }

        // Incrementa e salva
        $data['attempts']++;
        $this->saveData($key, $data);

        return true; // permitido
    }

    public function remainingAttempts(string $key): int
    {
        $data = $this->getData($key);

        if (time() > $data['reset_at']) {
            return $this->maxAttempts;
        }

        return max(0, $this->maxAttempts - $data['attempts']);
    }

    public function resetIn(string $key): int
    {
        $data = $this->getData($key);
        return max(0, $data['reset_at'] - time());
    }

    public function clear(string $key): void
    {
        $file = $this->getFilePath($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }
}