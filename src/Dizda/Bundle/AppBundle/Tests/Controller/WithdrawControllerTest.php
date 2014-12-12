<?php

namespace Dizda\Bundle\AppBundle\Tests\Controller;

use Dizda\Bundle\AppBundle\Tests\BaseFunctionalTestController;

/**
 * Class WithdrawControllerTest
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class WithdrawControllerTest extends BaseFunctionalTestController
{

    /**
     * @group functional
     */
    public function testGetWithdrawsAction()
    {
        $this->client->request('GET', '/withdraws.json');

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertCount(2, $content);
        $this->assertEquals('0.0003', $content[0]->total_inputs);
        $this->assertEquals('0.0001', $content[0]->total_outputs);
        $this->assertEquals('0.0001', $content[0]->fees);
        $this->assertTrue($content[0]->is_signed);
        $this->assertEquals('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f', $content[0]->txid);
        $this->assertNotNull($content[0]->withdrawed_at);
        $this->assertCount(1, $content[0]->withdraw_inputs);
        $this->assertCount(1, $content[0]->withdraw_outputs);
        $this->assertEquals('Keychain Fixture', $content[0]->keychain->name);
        $this->assertCount(0, $content[0]->signatures);
    }

    /**
     * @group functional
     */
    public function testGetWithdrawAction()
    {
        $this->client->request('GET', sprintf('/withdraws/%d.json', /* withdraw */ 1));

        $content = json_decode($this->client->getResponse()->getContent());

        // Withdraws group output
        $this->assertEquals('0.0003', $content->total_inputs);
        $this->assertEquals('0.0001', $content->total_outputs);
        $this->assertEquals('0.0001', $content->fees);
        $this->assertTrue($content->is_signed);
        $this->assertEquals('431c5231114ce2d00125ea4a88f4e4637b80fef1117a0b20606204e45cc3678f', $content->txid);
        $this->assertNotNull($content->withdrawed_at);
        $this->assertCount(1, $content->withdraw_inputs);
        $this->assertCount(1, $content->withdraw_outputs);
        $this->assertEquals('Keychain Fixture', $content->keychain->name);
        $this->assertCount(0, $content->signatures);

        // WithdrawDetail group output
        $this->assertEquals(
            '010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc50100000000ffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000',
            $content->raw_transaction
        );
        $this->assertEquals(
            '010000000155a3dd66c03bd64f6512fc47c9156db1a431946e07536d7ed5321df051d6bfc501000000fdfd0000483045022100a2054c60df4d2cad65b2a3970ec20c924c487389115ed5bced7dd684e353c78c02202d142f84317d4a26bb41542f5935068f28a59f48aa09f5bd84a7e4798220ea5b0147304402200c8abff78912bcfa787941d206b00ace246da11841f6ae39ebcd5fa42902825e02200223df3d31a4ed998439dda54d4370592c7ac188e64aa2e0efcd916146554ffb014c69522102a71ef05b31072d778b35f47d6204b80733db964498267f61dec2bdaaca22752121025bcd11b34f89704aba4d8f88d5e4d5db2a65ed1d6aabbc1f335f2eec771ee4e421024da9e2fb260317954c54df92d78051ef230de9a5aafef2592bb3b4f666209bcf53aeffffffff0210270000000000001976a914d356d4d8079f8556be4c8102ecf00cc63344e75488ac102700000000000017a914dec137068316fe8ddffdf05befb24d786e38adf98700000000',
            $content->raw_signed_transaction
        );
        $this->assertEquals('be5282178cacb7a04696963de62f674bef9a4510f7577d21585442c1eb8e8f2f', $content->withdraw_inputs[0]->txid);
        $this->assertFalse($content->withdraw_inputs[0]->address->is_external);
        $this->assertEquals(1, $content->withdraw_inputs[0]->address->derivation);
        $this->assertEquals(
            '522103ad50a5aa6e6210e00bcd95197cc318833f0016c769a7d291ba4fe49e43bed56621029dd61b0195ff5e69a6dbcc454f30fb55f0deeb34418de576830c674d33a0dbcb210210febba17348636dd1779ca2d86beea81ad065cfea924178bbc296d3c6ed372c53ae',
            $content->withdraw_inputs[0]->address->redeem_script
        );
        $this->assertEquals(
            '522103ad50a5aa6e6210e00bcd95197cc318833f0016c769a7d291ba4fe49e43bed56621029dd61b0195ff5e69a6dbcc454f30fb55f0deeb34418de576830c674d33a0dbcb210210febba17348636dd1779ca2d86beea81ad065cfea924178bbc296d3c6ed372c53ae',
            $content->withdraw_inputs[0]->address->redeem_script
        );
        $this->assertEquals('0.0001', $content->withdraw_outputs[0]->amount);
        $this->assertEquals('1LGTbdVSEbD9C37qXcpvVJ1egdBu8jYSeV', $content->withdraw_outputs[0]->to_address);
        $this->assertCount(0, $content->signatures);
    }

    /**
     * Try to sign the withdraw
     *
     * @group functional
     */
    public function testPostWithdrawAction()
    {
        $this->client->request('POST', sprintf('/withdraws/%d.json', /* withdraw */ 2), [
            'id' => 2,
            'raw_signed_transaction' => 'coucou',
            'signed_by' => '024929ebd103ec6ffaafcafd19806b3a404de9dcb6231d86bf1f9dbec23cd6059b', // Pubkey #2
            'is_signed' => false
        ]);

        $content = json_decode($this->client->getResponse()->getContent());

        $this->assertEquals('coucou', $content->raw_signed_transaction);

        // verify that the signature is present
        $this->assertEquals('Pubkey2 keychain1 fixture', $content->signatures[0]->name);
    }

    /**
     * Delete a withdraw
     *
     * @group functional
     */
    public function testDeleteWithdrawAction()
    {
        // count before
        $count = $this->em->getRepository('DizdaAppBundle:Withdraw')->findAll();
        $this->assertCount(2, $count);

        // perform request
        $this->client->request('DELETE', sprintf('/withdraws/%d.json', /* withdraw */ 2));

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        // count changes
        $count = $this->em->getRepository('DizdaAppBundle:Withdraw')->findAll();
        $this->assertCount(1, $count);
    }
}
