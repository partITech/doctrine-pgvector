
# PGVector for Doctrine

## Description

PGVector type for Doctrine

## Installation

```bash
composer require partitech/doctrine-pgvector
```

### Configuration Doctrine


```Yaml
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

## Utilisation

You can now use `vector` type in your entities :

```php
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
  */
  class YourEntity
  {

    #[ORM\Column(type: 'vector', length: 1024, nullable: true)]
    private $vectors;
    
  }
```

If you use `symfony console make:entity` add manually the `length` parameter attribute as vector. Length is your model embedding's dimention.

#### For example OpenAi use these dimensions: 

text-embedding-3-small : 1536

text-embedding-3-large : 3072 (customizable)

#### Mistral AI
Mistral-embed : 1024

Additionally, you should manually add an **HNSW** index to your vector's column. 
Be aware that dimension should be  2000 max for HNSW indexes.

#### L2 distance
```sql
CREATE INDEX ON items USING hnsw (embedding vector_l2_ops);
```

#### Inner product
```sql
CREATE INDEX ON items USING hnsw (embedding vector_ip_ops);
```

#### Cosine distance
```sql
CREATE INDEX ON items USING hnsw (embedding vector_cosine_ops);
```


## Basic usage: 

### distance

To get 

```sql
SELECT * FROM embeddings WHERE vectors <-> '[3,1,2]' < 5
```

use

```php
$floatArray = array_map(function() {
    return mt_rand(0, 1000000) / 1000000;
}, array_fill(0, 1024, null));

$query = $this->entityManager->createQuery(
    "SELECT i FROM App\Entity\Embeddings i ORDER BY distance(i.vectors, :vector) ASC"
);
$query->setParameter('vector', $floatArray, 'vector');
$results = $query->setMaxResults(5)->getResult();
dump($results);
```

```php
$qb = $this->entityManager->createQueryBuilder();
$qb->select('e')
    ->from('App:Embeddings', 'e')
    ->orderBy('distance(e.vectors, :vector)')
    ->setParameter('vector', $floatArray, 'vector')
    ->setMaxResults(5)
    ;
$result = $qb->getQuery()->getResult();
dump($result);
```

### Inner product

To get
```sql
SELECT (vectors <#> '[3,1,2]') * -1, * FROM embeddings
```
use
```php
$floatArray = array_map(function() {
    return mt_rand(0, 1000000) / 1000000;
}, array_fill(0, 1024, null));

$query = $this->entityManager->createQuery(
    "SELECT inner_product(e.vectors, :vector) , e FROM App\Entity\Embeddings e"
);
$query->setParameter('vector', $floatArray, 'vector');
$results = $query->setMaxResults(5)->getResult();
dump($results);
```

```php
$qb = $this->entityManager->createQueryBuilder();
$qb->select('e')
    ->addSelect('inner_product(e.vectors, :vector)')
    ->from('App:Embeddings', 'e')
    ->setParameter('vector', $floatArray, 'vector')
    ->setMaxResults(5)
    ;
$result = $qb->getQuery()->getResult();
dump($result);
```

### Cosine similarity

To get 
```sql 
SELECT 1 - (vectors <=> '[3,1,2]'), * FROM embeddings
```
use
```php
$floatArray = array_map(function() {
return mt_rand(0, 1000000) / 1000000;
}, array_fill(0, 1024, null));

$query = $this->entityManager->createQuery(
    "SELECT cosine_similarity(e.vectors, :vector) , e FROM App\Entity\Embeddings e"
);
$query->setParameter('vector', $floatArray, 'vector');
$results = $query->setMaxResults(5)->getResult();
dump($results);
```

```php
$qb = $this->entityManager->createQueryBuilder();
$qb->select('e')
    ->addSelect('cosine_similarity(e.vectors, :vector)')
    ->from('App:Embeddings', 'e')
    ->setParameter('vector', $floatArray, 'vector')
    ->setMaxResults(5)
    ;
$result = $qb->getQuery()->getResult();
dump($result);
```