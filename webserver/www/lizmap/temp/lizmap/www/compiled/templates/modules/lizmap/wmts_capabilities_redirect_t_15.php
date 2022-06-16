<?php 
if (jApp::config()->compilation['checkCacheFiletime'] &&
filemtime('C:\webserver\www\lizmap\lizmap/modules/lizmap/templates/wmts_capabilities.tpl') > 1616160726){ return false;
} else {
 require_once('C:\webserver\www\lizmap\lib\jelix/plugins/tpl/common/modifier.number_format.php');
function template_meta_39aa8c6c9818560f900162afdf3ad8ba($t){

}
function template_39aa8c6c9818560f900162afdf3ad8ba($t){
?><Capabilities version="1.0.0" xmlns="http://www.opengis.net/wmts/1.0"
                xmlns:gml="http://www.opengis.net/gml" xmlns:ows="http://www.opengis.net/ows/1.1"
                xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://www.opengis.net/wmts/1.0 http://schemas.opengis.net/wmts/1.0/wmtsGetCapabilities_response.xsd">
    <ows:ServiceIdentification>
        <ows:Title>Service de visualisation WMTS</ows:Title>
        <ows:ServiceType>OGC WMTS</ows:ServiceType>
        <ows:ServiceTypeVersion>1.0.0</ows:ServiceTypeVersion>
    </ows:ServiceIdentification>
    <ows:ServiceProvider>
        <ows:ProviderName></ows:ProviderName>
        <ows:ProviderSite xlink:href=""/>
        <ows:ServiceContact>
            <ows:IndividualName></ows:IndividualName>
            <ows:PositionName></ows:PositionName>
            <ows:ContactInfo>
                <ows:Phone>
                    <ows:Voice/>
                    <ows:Facsimile/>
                </ows:Phone>
                <ows:Address>
                    <ows:DeliveryPoint></ows:DeliveryPoint>
                    <ows:City></ows:City>
                    <ows:AdministrativeArea/>
                    <ows:PostalCode></ows:PostalCode>
                    <ows:Country></ows:Country>
                    <ows:ElectronicMailAddress></ows:ElectronicMailAddress>
                </ows:Address>
            </ows:ContactInfo>
        </ows:ServiceContact>
    </ows:ServiceProvider>
    <ows:OperationsMetadata>
        <ows:Operation name="GetCapabilities">
            <ows:DCP>
                <ows:HTTP>
                    <ows:Get xlink:href="<?php echo htmlspecialchars($t->_vars['url']); ?>">
                        <ows:Constraint name="GetEncoding">
                            <ows:AllowedValues>
                                <ows:Value>KVP</ows:Value>
                            </ows:AllowedValues>
                        </ows:Constraint>
                    </ows:Get>
                </ows:HTTP>
            </ows:DCP>
        </ows:Operation>
        <ows:Operation name="GetTile">
            <ows:DCP>
                <ows:HTTP>
                    <ows:Get xlink:href="<?php echo htmlspecialchars($t->_vars['url']); ?>">
                        <ows:Constraint name="GetEncoding">
                            <ows:AllowedValues>
                                <ows:Value>KVP</ows:Value>
                            </ows:AllowedValues>
                        </ows:Constraint>
                    </ows:Get>
                </ows:HTTP>
            </ows:DCP>
        </ows:Operation>
    </ows:OperationsMetadata>
    <Contents>
    <?php foreach($t->_vars['layers'] as $t->_vars['l']):?>

    <Layer>
        <ows:Identifier><?php echo $t->_vars['l']->name; ?></ows:Identifier>
        <ows:Title><?php echo $t->_vars['l']->title; ?></ows:Title>
        <Style isDefault="true">
            <ows:Identifier>default</ows:Identifier>
            <ows:Title>default</ows:Title>
        </Style>
        <ows:WGS84BoundingBox>
            <ows:LowerCorner><?php echo $t->_vars['l']->lowerCorner->x; ?> <?php echo $t->_vars['l']->lowerCorner->y; ?></ows:LowerCorner>
            <ows:UpperCorner><?php echo $t->_vars['l']->upperCorner->x; ?> <?php echo $t->_vars['l']->upperCorner->y; ?></ows:UpperCorner>
        </ows:WGS84BoundingBox>
        <Format><?php echo $t->_vars['l']->imageFormat; ?></Format>
        <?php foreach($t->_vars['l']->tileMatrixSetLinkList as $t->_vars['tileMatrixSetLink']):?>

        <TileMatrixSetLink>
            <TileMatrixSet><?php echo $t->_vars['tileMatrixSetLink']->ref; ?></TileMatrixSet>
            <TileMatrixSetLimits>
                <?php foreach($t->_vars['tileMatrixSetLink']->tileMatrixLimits as $t->_vars['tileMatrixLimit']):?>

                <TileMatrixLimits>
                    <TileMatrix><?php echo $t->_vars['tileMatrixLimit']->id; ?></TileMatrix>
                    <MinTileRow><?php echo $t->_vars['tileMatrixLimit']->minRow; ?></MinTileRow>
                    <MaxTileRow><?php echo $t->_vars['tileMatrixLimit']->maxRow; ?></MaxTileRow>
                    <MinTileCol><?php echo $t->_vars['tileMatrixLimit']->minCol; ?></MinTileCol>
                    <MaxTileCol><?php echo $t->_vars['tileMatrixLimit']->maxCol; ?></MaxTileCol>
                </TileMatrixLimits>
                <?php endforeach;?>

            </TileMatrixSetLimits>
        </TileMatrixSetLink>
        <?php endforeach;?>
    </Layer>
    <?php endforeach;?>
    <?php foreach($t->_vars['tileMatrixSetList'] as $t->_vars['tileMatrixSet']):?>
    <TileMatrixSet>
        <ows:Identifier><?php echo $t->_vars['tileMatrixSet']->ref; ?></ows:Identifier>
        <ows:SupportedCRS><?php echo $t->_vars['tileMatrixSet']->ref; ?></ows:SupportedCRS>
        <?php foreach($t->_vars['tileMatrixSet']->tileMatrixList as $t->_vars['k']=>$t->_vars['tileMatrix']):?>

        <TileMatrix>
            <ows:Identifier><?php echo $t->_vars['k']; ?></ows:Identifier>
            <ScaleDenominator><?php echo jtpl_modifier_common_number_format($t->_vars['tileMatrix']->scaleDenominator,16,'.',''); ?></ScaleDenominator>
            <TopLeftCorner><?php echo $t->_vars['tileMatrix']->left; ?> <?php echo $t->_vars['tileMatrix']->top; ?></TopLeftCorner>
            <TileWidth>256</TileWidth>
            <TileHeight>256</TileHeight>
            <MatrixWidth><?php echo $t->_vars['tileMatrix']->col; ?></MatrixWidth>
            <MatrixHeight><?php echo $t->_vars['tileMatrix']->row; ?></MatrixHeight>
        </TileMatrix>
        <?php endforeach;?>

    </TileMatrixSet>
    <?php endforeach;?>
    </Contents>
</Capabilities>
<?php 
}
return true;}
