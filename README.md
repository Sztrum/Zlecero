# Zlecero

Aplikacja webowa do obsługi zapytań, ofert, zleceń, maili i plików klientów w jednym panelu.

Projekt jest skierowany do firm usługowo-produkcyjnych, które pracują głównie na mailach, telefonach i ręcznych procesach. Pierwszą niszą są drukarnie, firmy reklamowe, oklejanie aut, szyldy, folie i banery.

Podstawowy flow aplikacji:

```txt
mail od klienta
→ zapytanie
→ oferta / wycena
→ akceptacja klienta
→ zlecenie
→ status realizacji
→ historia kontaktu
```

## Technologie

* Backend/API: Laravel 13, PHP 8.5
* Baza danych: MySQL 8
* Cache/kolejki: Redis
* Panel admina/pracownika: React, TypeScript, Tailwind, Vite
* Panel klienta: React, TypeScript, Tailwind
* Publiczny front SEO: Laravel Blade, Tailwind, Vite
* Import maili: IMAP na start, później Gmail API / Microsoft Graph

## Architektura

Projekt działa jako modularny monolit w `app/V1/**`.

Główna zasada:

```txt
UI → Application → Domain
Infrastructure = adaptery
```

Główne moduły:

```txt
Auth
Organization
User
Customer
Mailbox
Inquiry
Offer
Order
Workflow
CustomerPortal
Notification
ActivityLog
```

## MVP

Pierwsza wersja obejmuje:

* logowanie,
* organizacje i użytkowników,
* klientów,
* import maili,
* tworzenie zapytań z maili,
* załączniki,
* statusy,
* notatki,
* odpowiedź do klienta z panelu,
* ręczne tworzenie oferty,
* wysłanie oferty,
* akceptację oferty przez link,
* utworzenie zlecenia po akceptacji.

W pierwszej wersji nie budujemy pełnego ERP, magazynu, faktur, księgowości ani zaawansowanego wyceniatora.
