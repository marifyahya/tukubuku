# Global Coding Standards

## Coding Standards (Wajib untuk semua project)
- Gunakan Repository & Service Pattern
- Ikuti PSR-12 coding style
- Project laravel tiap Request selalu buatkan file Request

## Git Convention
- Commit message: conventional commits (feat:, fix:, docs:, refactor:, test:, chore:)
- Wajib review sebelum merge ke main
- commit mesage gunakan bahasa inggris

## Penamaan
- Classes: PascalCase (BookController, CartService)
- Methods/functions: camelCase (getUserById, calculateTotal)
- Variables: camelCase (bookPrice, userCount)
- Database tables: snake_case, plural (books, categories, order_items)

## Penting!
Global rules ini akan dikombinasikan dengan project rules (bukan di-override) [citation:3].
Jika ada konflik, project rules memiliki prioritas lebih tinggi untuk aturan non-keamanan [citation:6].
Aturan keamanan/keselamatan dari global rules tetap berlaku sebagai baseline minimum.

## Query & Database
- Pastikan dalam pembuatan query eloquent atau raw jangan sampai berat
