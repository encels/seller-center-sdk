<?php

declare(strict_types=1);

namespace Linio\SellerCenter\Order;

use Linio\SellerCenter\Factory\Xml\Order\OrderItemsFactory;
use Linio\SellerCenter\LinioTestCase;
use Linio\SellerCenter\Model\Order\OrderItem;
use Linio\SellerCenter\Model\Order\OrderItems;

class OrderItemsTest extends LinioTestCase
{
    public function testItFindsAndReturnsTheOrderItemByOrderItemId(): void
    {
        $response = simplexml_load_string($this->getResponseMock());
        $OrderItems = OrderItemsFactory::make($response->Body);

        $OrderItem = $OrderItems->findByOrderItemId(6750999);

        $this->assertInstanceOf(OrderItem::class, $OrderItem);
        $this->assertTrue($OrderItem->getOrderItemId() == 6750999);
    }

    public function testItReturnsAnEmptyValueWhenNoOrderItemWasFound(): void
    {
        $response = simplexml_load_string($this->getResponseMock());
        $OrderItems = OrderItemsFactory::make($response->Body);

        $OrderItem = $OrderItems->findByOrderItemId(4150769);

        $this->assertNull($OrderItem);
    }

    public function testItReturnsACollectionOfOrderItemsBySetStatus(): void
    {
        $simpleXml = simplexml_load_string(
            '<Body>
                        <OrderItems>
                          <OrderItem>
                            <OrderItemId>1</OrderItemId>
                            <PurchaseOrderId>123456</PurchaseOrderId>
                            <PurchaseOrderNumber>ABC-123456</PurchaseOrderNumber>
                            <PackageId>MPDS-200131783-9800</PackageId>
                          </OrderItem>                      
                          <OrderItem>
                            <PurchaseOrderId>78910</PurchaseOrderId>
                            <PurchaseOrderNumber>CDE-78910</PurchaseOrderNumber>
                          </OrderItem>
                        </OrderItems>
                      </Body>'
        );

        $orderItems = OrderItemsFactory::makeFromStatus($simpleXml);

        $this->assertInstanceOf(OrderItems::class, $orderItems);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $orderItems->all());
    }

    public function getResponseMock(): string
    {
        return '<SuccessResponse>
                 <Head>
                      <RequestId/>
                      <RequestAction>GetOrderItems</RequestAction>
                      <ResponseType>OrderItems</ResponseType>
                      <Timestamp>2019-01-18T06:54:50-0500</Timestamp>
                 </Head>
                 <Body>
                      <OrderItems>
                           <OrderItem>
                                <OrderItemId>6750999</OrderItemId>
                                <ShopId>7208215</ShopId>
                                <OrderId>4758978</OrderId>
                                <Name>MEGIR 5006 RELOJ ACERO INOXIDABLE ROSA</Name>
                                <Sku>DJFKLJOEDKLFJ</Sku>
                                <Variation>Talla Única</Variation>
                                <ShopSku>ME803FA0UEI9YLCO-6073653</ShopSku>
                                <ShippingType>Dropshipping</ShippingType>
                                <ItemPrice>89900.00</ItemPrice>
                                <PaidPrice>89900.00</PaidPrice>
                                <Currency>COP</Currency>
                                <WalletCredits>0.00</WalletCredits>
                                <TaxAmount>0.00</TaxAmount>
                                <CodCollectableAmount/>
                                <ShippingAmount>0.00</ShippingAmount>
                                <ShippingServiceCost>7000.00</ShippingServiceCost>
                                <VoucherAmount>0</VoucherAmount>
                                <VoucherCode/>
                                <Status>pending</Status>
                                <IsProcessable>1</IsProcessable>
                                <ShipmentProvider>LOGISTICA</ShipmentProvider>
                                <IsDigital>0</IsDigital>
                                <DigitalDeliveryInfo/>
                                <TrackingCode>1000414030800</TrackingCode>
                                <TrackingCodePre/>
                                <Reason/>
                                <ReasonDetail/>
                                <PurchaseOrderId>0</PurchaseOrderId>
                                <PurchaseOrderNumber/>
                                <PackageId>1000414030800</PackageId>
                                <PromisedShippingTime>2018-10-16 20:00:00</PromisedShippingTime>
                                <ExtraAttributes/>
                                <ShippingProviderType>express</ShippingProviderType>
                                <CreatedAt>2018-10-13 23:08:34</CreatedAt>
                                <UpdatedAt>2018-10-14 13:30:50</UpdatedAt>
                                <ReturnStatus/>
                           </OrderItem>
                      </OrderItems>
                 </Body>
            </SuccessResponse>';
    }
}
