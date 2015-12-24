<?php


class IdModel extends Model
{
    protected static $_sit_no_offset = 100;
    public function tableName(){
        return 'c_id';
    }

    //获取席位编号
    public function getSitNo(){
        $sql = "update " . $this->tableName() . " set id = LAST_INSERT_ID(id +1) where name='sit_no'";
        $this->execute($sql);
        return ('DDMG'.str_pad(self::$_sit_no_offset+$this->db->insertId(),5,0,STR_PAD_LEFT));
    }
}