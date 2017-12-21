# Lerma
Multi-screwdriver for the database

***
### Preview:
***

```PHP
INSERT INTO `lerma` (`name`, `num`) VALUES
('Aero\\test\\Aero', 111),
('Lerma', 111),
('Migrate', 111),
('Database', 111),
('Configures', 222),
('Interfaces', 333),
('LermaDrivers', 333);
```
```PHP
$table = 'lerma';

$sql = [ 'SELECT * FROM %s LIMIT 7', $table ]; # or 'SELECT * FROM lerma'

$query = Lerma::query( $sql ) -> fetchAll( Lerma::FETCH_UNIQUE );

Lerma::prepare( 'INSERT INTO lerma ( name, num ) VALUES ( ?, ? )', $query );
```
