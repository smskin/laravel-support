<?php

namespace SMSkin\LaravelSupport\Models;

class EnumItem
{
    public string $id;

    public string $title;

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }
}
