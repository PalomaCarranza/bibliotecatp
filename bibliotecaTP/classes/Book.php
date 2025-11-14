<?php

require_once __DIR__ . '/../functions.php';

class Book
{
    public $title;
    public $author;
    public $year;
    public $available;

    public function __construct(string $title, string $author, $year = '', $available = false)
    {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->available = (bool)$available;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'year' => $this->year,
            'available' => $this->available,
        ];
    }

    // lee todos los libros
    public static function all(): array
    {
        return read_books();
    }

    // guarda un nuevo libro
    public static function create(array $data): bool
    {
        $books = read_books();
        // normalizar campos
        $book = [
            'title' => trim((string)($data['title'] ?? '')),
            'author' => trim((string)($data['author'] ?? '')),
            'year' => isset($data['year']) ? (int)$data['year'] : '',
            'available' => !empty($data['available']) ? true : false,
        ];
        $books[] = $book;
        return save_books($books);
    }

    public static function count(): int
    {
        return count(read_books());
    }
}
