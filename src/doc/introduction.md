# Doctrine PgVector

Partitech Doctrine PgVector is a Doctrine extension for integrating PostgreSQL vector types into your PHP projects using Doctrine ORM. This module simplifies using vectors in your database by providing calculation functions such as distance, inner product, and cosine similarity.

## 📦 Installation

You can install Partitech Doctrine PgVector via Composer:

```shell
composer require partitech/doctrine-pgvector
```

## ⚙️ Configuration

Add the types and functions to your Doctrine configuration:

```yaml
doctrine:
    dbal:
        types:
            vector: Partitech\DoctrinePgVector\Type\VectorType
    orm:
        dql:
            string_functions:
                distance: Partitech\DoctrinePgVector\Query\Distance
                inner_product: Partitech\DoctrinePgVector\Query\InnerProduct
                cosine_similarity: Partitech\DoctrinePgVector\Query\CosineSimilarity
```

## 🛠️ Usage

### Vector Types

Use vector types directly in your Doctrine entities:

```php
/** @Column(type="vector") */
private $vector;
```

### Available DQL Functions

- `distance(vector, vector)`
- `inner_product(vector, vector)`
- `cosine_similarity(vector, vector)`

These functions can be directly used in your Doctrine queries.

## 📌 Examples

Example query using distance:

```php
$query = $entityManager->createQuery(
    'SELECT e FROM Entity e ORDER BY distance(e.vector, :inputVector) ASC'
);
$query->setParameter('inputVector', [0.1, 0.2, 0.3]);
$result = $query->getResult();
```

## ✅ Testing and Development

To run unit tests:

```shell
composer test
```

## 🤝 Contribution

Contributions are welcome! Check the [issues page](https://github.com/partitech/doctrine-pgvector/issues) to start contributing or submit a Pull Request.

## 📄 License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for more details.

