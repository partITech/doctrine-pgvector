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

