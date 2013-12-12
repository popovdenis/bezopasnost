<?php
/**
 * Class seomodel
 *
 * seo model
 *
 * @author   Popov
 * @access   public
 * @package  seomodel.class.php
 * @created  Sat May 23 20:51:10 EEST 2009
 */
class seo_mdl extends Model
{
    /**
     * Constructor of seomodel
     *
     * @access  public
     */
    function __constructor( )
    {
        parent::Model( );
    }

    /**
     * @param array $seoData
     * @return array
     */
    public function getSeoData( $seoData = array( ) )
    {
        $result = array( );

        $this->db->from( "seo" );

        $query = "select * from seo where 1=1 ";
        if ( isset( $seoData['page_url'] ) && !empty( $seoData['page_url'] ) )
        {
            $query .= " and page_url = " . clean( $seoData['page_url'] );
        }

        if ( $query = $this->db->query( $query ) )
        {
            $result = $query->result();
        }

        return $result;
    }

    /**
     * @param array $seoData
     * @return bool
     */
    public function setSeoData( $seoData = array( ) )
    {
        if ( empty( $seoData ) || !isset( $seoData['page_url'] ) ) {
            return false;
        }
        $this->deleteSeoData( $seoData['page_url'] );

        $info = array(
            'page_url' => $this->input->xss_clean( $seoData['page_url'] ),
            'title' => empty( $seoData['title'] ) ? null : $this->input->xss_clean( $seoData['title'] ),
            'keywords' => empty( $seoData['keywords'] ) ? null : $this->input->xss_clean( $seoData['keywords'] ),
            'description' => empty( $seoData['description'] ) ? null : $this->input->xss_clean( $seoData['description'] ) );

        if ( !$this->db->insert( 'seo', $info ) ) {
            throw new Exception( $this->db->_error_message( ) );
        }

        return true;
    }

    /**
     * @param $page_url
     * @return bool
     */
    public function deleteSeoData( $page_url )
    {
        if ( empty( $page_url ) ) {
            return false;
        }

        return $this->db->delete( 'seo', array( 'page_url' => $page_url ) );
    }

    /**
     * @param $item
     * @param $itemSlug
     * @return bool
     */
    public function setMeta( $item = null, $itemSlug )
    {
        if ( empty( $item ) && empty( $itemSlug ) ) {
            return false;
        }

        $this->deleteMeta( $item->item_id, $itemSlug );

        $info = array(
            'item_id'       => $item->item_id,
            'title'         => $item->item_seo_title,
            'keywords'      => $item->item_seo_keywords,
            'description'   => $item->item_seo_description,
            'item_slug'     => $itemSlug
        );

        if ( !$this->db->insert( 'seo', $info ) ) {
            throw new Exception( $this->db->_error_message( ) );
        }

        return true;
    }

    /**
     * @param null $itemId
     * @param string $itemSlug
     * @return bool
     */
    public function deleteMeta( $itemId = null, $itemSlug = '' )
    {
        if ( empty( $itemId ) && empty( $itemSlug ) ) {
            return false;
        }

        $where = array();
        if( !empty( $itemId ) ) $where['item_id'] = $itemId;
        if( !empty( $itemSlug ) ) $where['item_slug'] = $itemSlug;

        return $this->db->delete( 'seo', $where );
    }
}

?>