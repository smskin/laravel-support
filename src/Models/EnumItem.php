<?php

namespace SMSkin\LaravelSupport\Models;

class EnumItem
{
    public string $id;

    public ?string $title = null;

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string|null $title
     * @return EnumItem
     */
    public function setTitle(?string $title): EnumItem
    {
        $this->title = $title;
        return $this;
    }
}
