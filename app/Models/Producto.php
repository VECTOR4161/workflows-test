<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria_id',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'unidad_medida',
        'imagen',
        'estado',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['margen_utilidad', 'tiene_stock_bajo'];

    public const CODIGO_PREFIJO = 'PROD';

    // Relación con categoría
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // Scope para productos activos
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', true);
    }

    // Scope para productos inactivos
    public function scopeInactivos(Builder $query): Builder
    {
        return $query->where('estado', false);
    }

    // Scope para productos con stock bajo
    public function scopeStockBajo(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    // Accessor para margen de utilidad
    public function getMargenUtilidadAttribute(): float
    {
        if ($this->precio_compra == 0) {
            return 0;
        }

        return round((($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100, 2);
    }

    // Accessor para verificar stock bajo
    public function getTieneStockBajoAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    // Generar código automático
    public static function generarCodigo(): string
    {
        return DB::transaction(function () {
            $ultimo = self::lockForUpdate()->orderBy('id', 'desc')->first();
            $numero = $ultimo ? (int) substr($ultimo->codigo, strlen(self::CODIGO_PREFIJO)) + 1 : 1;

            return self::CODIGO_PREFIJO.str_pad((string) $numero, 6, '0', STR_PAD_LEFT);
        });
    }
}
