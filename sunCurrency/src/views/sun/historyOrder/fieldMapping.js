export const labelI18n = () => {
  const { t } = useI18n()
  const fieldMapping = {
    status: t('index.state_0'),
    order_no: t('index.order_no'),
    pledge_amount: t('index.pledge_amount'),
    loan_rate: t('index.loan_rate'),
    loan_ratio: t('index.loan_ratio'),
    total_award: t('index.income_1'),
    created_at: t('index.created_at'),
    expire_time: t('index.become_due'),
    loan_amount: t('index.loan_amount'),
    df_amount: t('loan.day_income_jiang'),
    deposit_amount: t('index.deposit_amount_1'),
    daily_rate: t('index.daily_rate_11'),
    deposit_time: t('index.deposit_time'),
    created_at_zhiya:t('index.ime')
  }
  return {
    fieldMapping,
  }
}
