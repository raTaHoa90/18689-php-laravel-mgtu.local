<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'product_id', // для какого товара заводится характеристика
    'caption',    // название характеристики
    'value',      // значение характеристики
    'type_param', // тип характеристики
    'list_id'     // ссылка на лист значений, если выбран соответствующий тип
])]
class ProductParam extends Model {
    const
        TYPE_TEXT = 0, // текст
        TYPE_LIST_VALUES = 1, // лист значений
        TYPE_LIST_VALUES_USER_SELECT = 2, // лист значений, который выбирает сам пользователь

        TYPES = [
            self::TYPE_TEXT => 'Любое значение',
            self::TYPE_LIST_VALUES => 'Значение из списка вариантов',
            self::TYPE_LIST_VALUES_USER_SELECT => 'Значения из списка вариантов, которые может выбрать пользователь'
        ],
        TYPES_OF_LIST = [self::TYPE_LIST_VALUES_USER_SELECT, self::TYPE_LIST_VALUES];

    protected $table = 'product_params';

    // возвражает товар, к которому относится параметр
    function product(): Product {
        return Product::find($this->product_id);
    }

    // возвращает список значений, если выбран тип листа
    function list(): ?ParamList {
        if(!in_array($this->type_param, static::TYPES_OF_LIST) ||
            $this->list_id == 0
        ) return null;

        return ParamList::find($this->list_id);
    }

    function getValue($nameList): string {
        if(!in_array($this->type_param, static::TYPES_OF_LIST))
            return $this->value;

        $list = $this->list();
        if($this->type_param == static::TYPE_LIST_VALUES_USER_SELECT){
            $result = ["<div class='-values'>"];
            $ids = explode(',',$this->value);
            foreach($ids as $i => $id)
                $result[] = '<label><input type=radio name="'
                    .$nameList
                    .'" value='.$id
                    .($i == 0 ? ' checked' : '')
                    .' style="display:none">'
                    .$list->getValueByID($id, true)
                    .'</label>'
                ;
            $result[] = '</div>';
            return implode(' ', $result);
        }

        return $list->getValueByID($this->value, false);
    }

    function getValueSet(int $valueID): string {
        if(!in_array($this->type_param, static::TYPES_OF_LIST))
            return $this->value;

        $list = $this->list();
        if($this->type_param == static::TYPE_LIST_VALUES_USER_SELECT){
            $ids = explode(',',$this->value);
            if(!in_array($valueID, $ids))
                $valueID = array_shift($ids);
            return "<div class='-values'>". $list->getValueByID($valueID, false) . '</div>';
        }

        return $list->getValueByID($this->value, false);
    }


}
