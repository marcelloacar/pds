<?php

use App\Shop\Customers\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BRStatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared("
INSERT INTO `states` VALUES ('Acre', 'AC', 30);
INSERT INTO `states` VALUES ('Alagoas', 'AL', 30);
INSERT INTO `states` VALUES ('Amapá', 'AP', 30);
INSERT INTO `states` VALUES ('Amazonas', 'AM', 30);
INSERT INTO `states` VALUES ('Bahia', 'BA', 30);
INSERT INTO `states` VALUES ('Ceará', 'CE', 30);
INSERT INTO `states` VALUES ('Distrito Federal', 'DF', 30);
INSERT INTO `states` VALUES ('Espírito Santo', 'ES', 30);
INSERT INTO `states` VALUES ('Goiás', 'GO', 30);
INSERT INTO `states` VALUES ('Maranhão', 'MA', 30);
INSERT INTO `states` VALUES ('Mato Grosso', 'MT', 30);
INSERT INTO `states` VALUES ('Mato Grosso do Sul', 'MS', 30);
INSERT INTO `states` VALUES ('Minas Gerais', 'MG', 30);
INSERT INTO `states` VALUES ('Pará', 'PA', 30);
INSERT INTO `states` VALUES ('Paraíba', 'PB', 30);
INSERT INTO `states` VALUES ('Paraná', 'PR', 30);
INSERT INTO `states` VALUES ('Pernambuco', 'PE', 30);
INSERT INTO `states` VALUES ('Piauí', 'PI', 30);
INSERT INTO `states` VALUES ('Rio de Janeiro', 'RJ', 30);
INSERT INTO `states` VALUES ('Rio Grande do Norte', 'RN', 30);
INSERT INTO `states` VALUES ('Rio Grande do Sul', 'RS', 30);
INSERT INTO `states` VALUES ('Rondônia', 'RO', 30);
INSERT INTO `states` VALUES ('Roraima', 'RR', 30);
INSERT INTO `states` VALUES ('Santa Catarina', 'SC', 30);
INSERT INTO `states` VALUES ('São Paulo', 'SP', 30);
INSERT INTO `states` VALUES ('Sergipe', 'SE', 30);
INSERT INTO `states` VALUES ('Tocantins', 'TO', 30);
        ");
        DB::commit();
    }
}