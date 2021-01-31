<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Class ArtisticFilter
 */
class ArtisticFilter extends ValueEffectQualifier
{
    const AL_DENTE   = 'al_dente';
    const ATHENA     = 'athena';
    const AUDREY     = 'audrey';
    const AURORA     = 'aurora';
    const DAGUERRE   = 'daguerre';
    const EUCALYPTUS = 'eucalyptus';
    const FES        = 'fes';
    const FROST      = 'frost';
    const HAIRSPRAY  = 'hairspray';
    const HOKUSAI    = 'hokusai';
    const INCOGNITO  = 'incognito';
    const LINEN      = 'linen';
    const PEACOCK    = 'peacock';
    const PRIMAVERA  = 'primavera';
    const QUARTZ     = 'quartz';
    const RED_ROCK   = 'red_rock';
    const REFRESH    = 'refresh';
    const SIZZLE     = 'sizzle';
    const SONNET     = 'sonnet';
    const UKULELE    = 'ukulele';
    const ZORRO      = 'zorro';

    /**
     * ArtisticFilter constructor.
     *
     * @param string $filter The artistic filter name.
     */
    public function __construct($filter = null)
    {
        parent::__construct(MiscEffect::ARTISTIC_FILTER, $filter);
    }

    /**
     * Artistic Filter alDente.
     *
     * @return ArtisticFilter
     */
    public static function alDente()
    {
        return new static(self::AL_DENTE);
    }

    /**
     * Artistic filter athena.
     *
     * @return ArtisticFilter
     */
    public static function athena()
    {
        return new static(self::ATHENA);
    }

    /**
     * Artistic filter audrey.
     *
     * @return ArtisticFilter
     */
    public static function audrey()
    {
        return new static(self::AUDREY);
    }

    /**
     * Artistic filter aurora.
     *
     * @return ArtisticFilter
     */
    public static function aurora()
    {
        return new static(self::AURORA);
    }

    /**
     * Artistic filter daguerre.
     *
     * @return ArtisticFilter
     */
    public static function daguerre()
    {
        return new static(self::DAGUERRE);
    }

    /**
     * Artistic filter eucalyptus.
     *
     * @return ArtisticFilter
     */
    public static function eucalyptus()
    {
        return new static(self::EUCALYPTUS);
    }

    /**
     * Artistic filter fes.
     *
     * @return ArtisticFilter
     */
    public static function fes()
    {
        return new static(self::FES);
    }

    /**
     * Artistic filter frost.
     *
     * @return ArtisticFilter
     */
    public static function frost()
    {
        return new static(self::FROST);
    }

    /**
     * Artistic filter hairspray.
     *
     * @return ArtisticFilter
     */
    public static function hairspray()
    {
        return new static(self::HAIRSPRAY);
    }

    /**
     * Artistic filter hokusai.
     *
     * @return ArtisticFilter
     */
    public static function hokusai()
    {
        return new static(self::HOKUSAI);
    }

    /**
     * Artistic filter incognito.
     *
     * @return ArtisticFilter
     */
    public static function incognito()
    {
        return new static(self::INCOGNITO);
    }

    /**
     * Artistic filter linen.
     *
     * @return ArtisticFilter
     */
    public static function linen()
    {
        return new static(self::LINEN);
    }

    /**
     * Artistic filter peacock.
     *
     * @return ArtisticFilter
     */
    public static function peacock()
    {
        return new static(self::PEACOCK);
    }

    /**
     * Artistic filter primavera.
     *
     * @return ArtisticFilter
     */
    public static function primavera()
    {
        return new static(self::PRIMAVERA);
    }

    /**
     * Artistic filter quartz.
     *
     * @return ArtisticFilter
     */
    public static function quartz()
    {
        return new static(self::QUARTZ);
    }

    /**
     * Artistic filter redRock.
     *
     * @return ArtisticFilter
     */
    public static function redRock()
    {
        return new static(self::RED_ROCK);
    }

    /**
     * Artistic filter refresh.
     *
     * @return ArtisticFilter
     */
    public static function refresh()
    {
        return new static(self::REFRESH);
    }

    /**
     * Artistic filter sizzle.
     *
     * @return ArtisticFilter
     */
    public static function sizzle()
    {
        return new static(self::SIZZLE);
    }

    /**
     * Artistic filter sonnet.
     *
     * @return ArtisticFilter
     */
    public static function sonnet()
    {
        return new static(self::SONNET);
    }

    /**
     * Artistic filter ukulele.
     *
     * @return ArtisticFilter
     */
    public static function ukulele()
    {
        return new static(self::UKULELE);
    }

    /**
     * Artistic filter zorro.
     *
     * @return ArtisticFilter
     */
    public static function zorro()
    {
        return new static(self::ZORRO);
    }
}
