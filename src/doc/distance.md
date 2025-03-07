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

