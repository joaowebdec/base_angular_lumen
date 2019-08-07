<?php

use Illuminate\Database\Seeder;
use App\Mappings;

class MappingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->bmgSaqueMapping();
    }

    /**
     * Mapeamento para o saque do bmg
     * 
     * @return void
     */
    private function bmgSaqueMapping() : void
    {
        Mappings::create([
            'columns' => json_encode([
                [
                    'label'       => 'CPF',
                    'value'       => 'cpf',
                    'required'    => 'S',
                    'description' => 'CPF Cliente'
                ],
                [
                    'label'       => 'Nome',
                    'value'       => 'name',
                    'required'    => 'N',
                    'description' => 'Nome do cliente'
                ],
                [
                    'label' => 'Celular',
                    'value' => 'celphone',
                    'required' => 'S',
                    'description' => 'Número(s) do celular do cliente, caso tenha mais de 1 os numeros devem ser colocados separadas por ",". Ex.: 2126544457,1126064589'
                ],
                [
                    'label'       => 'Agência',
                    'value'       => 'agency',
                    'required'    => 'S',
                    'description' => 'Número da Agência'
                ],
                [
                    'label'       => 'Digito da agência',
                    'value'       => 'agency_digit',
                    'required'    => 'N',
                    'description' => 'Digito verificador da Agência'
                ],
                [
                    'label'       => 'Banco',
                    'value'       => 'bank',
                    'required'    => 'S',
                    'description' => 'Número do banco'
                ],
                [
                    'label'       => 'Conta',
                    'value'       => 'account',
                    'required'    => 'S',
                    'description' => 'Número da conta'
                ],
                [
                    'label'       => 'Digito da conta',
                    'value'       => 'account_digit',
                    'required'    => 'N',
                    'description' => 'Digito verificador da conta'
                ],
                [
                    'label'       => 'Tipo do Saque',
                    'value'       => 'type',
                    'required'    => 'S',
                    'description' => '1-SaqueAutorizado
                    2-SaqueAutorizadoParcelado
                    3-SaqueAutorizadoLojista
                    4-SaqueAutorizadoParceladoLojista
                    5-SaqueAutorizadoDecimoTerceiro'
                ],
                [
                    'label'       => 'Finalidade do crédito',
                    'value'       => 'finality_credit',
                    'required'    => 'S',
                    'description' => 'Código da finalidade de crédito:
                    1 - Conta Movimento
                    2- Conta Poupança'
                ],
                [
                    'label'       => 'Código da entidade',
                    'value'       => 'entity_code',
                    'required'    => 'S',
                    'description' => 'Código da entidade'
                ],
                [
                    'label'       => 'Código do orgão',
                    'value'       => 'sequential_organ',
                    'required'    => 'N',
                    'description' => 'Código órgão quando entidade exigir'
                ],
                [
                    'label'       => 'Código da loja',
                    'value'       => 'store_code',
                    'required'    => 'N',
                    'description' => 'Codigo da loja onde a Proposta vai ser
                    implantada. Caso não seja informado sera
                    utilizado a loja parametrizada para o Usuário.
                    Se informado, o código deve ser igual ao
                    código de loja do usuário, ou ser o código de
                    uma loja que pertença ao mesmo grupo de
                    loja do Usuário deste que este tenha
                    permissão de Visão de Grupo de Loja.'
                ],
                [
                    'label'       => 'Código de situação do servidor',
                    'value'       => 'server_situation_code',
                    'required'    => 'N',
                    'description' => 'Caso o órgão informado tiver situação
                    funcional cadastrada e necessário infomar o
                    codigoSituacaoServidor caso contrario não e
                    necessário informar
                    Ex: entidade federal civil que possui situação
                    funcional cadastrada então neste caso e
                    necessário informar esse campo'
                ],
                [
                    'label'       => 'Forma de crédito',
                    'value'       => 'form_credit',
                    'required'    => 'S',
                    'description' => 'Código da Forma de crédito:
                    TedContaSalario(1)
                    TedContaCredito(2)
                    OrdemPagamento(3)
                    AgenciaPagadoraBMG(4)
                    SemFinanceiro(5)
                    CartaoBMBCash(6) - É um Cartão de Saque
                    Opcional(7)
                    BMGCheque(8)
                    CartaoDinheiroRapido(9) - É um Cartão de Saque
                    ChequeAdministrativo(12) - Cheque ADM
                    SaqueTecban(14) 
                    SaqueOpAtm(15)'
                ],
                [
                    'label'       => 'Código do banco (Ordem Pagamento)',
                    'value'       => 'bank_code_payment_order',
                    'required'    => 'N',
                    'description' => 'Codigo do banco para OP. Informar "0" (zero) caso não seja OP ou deixe em branco (Sem mapeamento).'
                ],
                [
                    'label'       => 'Código da forma de envio',
                    'value'       => 'shipping_form_code',
                    'required'    => 'S',
                    'description' => 'Código da forma de envio:
                    Balcao(0)
                    Email(1)
                    Sedex(2)
                    GetNet(3)
                    MotoBoy(4)
                    EntregaPessoal(5)
                    CartaoBMGFacilInternet(6)
                    CartaoBMGFacilInternetSenhaValidada(7)
                    DocumentoDigital(8)'
                ]
            ])
        ]);
    }
}
