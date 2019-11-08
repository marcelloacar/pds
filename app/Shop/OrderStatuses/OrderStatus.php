<?php

namespace App\Shop\OrderStatuses;

use App\Shop\Orders\Order;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'color',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getLabelAttribute(){

        switch($this->name){
            case 'paid':
                return  "Pagamento Confirmado";
                break;
            case 'pending':
                return  "Pendente";
                break;
            case 'error':
                return  "Erro";
                break;
            case 'on-delivery':
                return  "Aguardando retirada";
                break;
            case 'ordered':
                return  "Pedido Recebido";
                break;
        }

        return "";
    }
}
