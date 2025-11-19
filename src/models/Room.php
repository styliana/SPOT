<?php

class Room {
    private $id;
    private $name;
    private $workspaces;
    private $type;
    private $description;

    public function __construct(string $id, string $name, int $workspaces, string $type, ?string $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->workspaces = $workspaces;
        $this->type = $type;
        $this->description = $description;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getWorkspaces(): int { return $this->workspaces; }
    public function getType(): string { return $this->type; }
    public function getDescription(): ?string { return $this->description; }
}