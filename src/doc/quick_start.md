## Quick Start

Use `vector` type in your entities :

```php
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VectorsRepository::class)]
#[ORM\Table(name: 'app__vectors')]
class Vectors
{

#[ORM\Column(type: 'vector', length: 1024, nullable: true)]
private array $vectors = [];

}
```
> [!WARNING]
> If you use `symfony console make:entity` add manually the `length` parameter attribute as vector. Length is your model embedding's dimension.

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

