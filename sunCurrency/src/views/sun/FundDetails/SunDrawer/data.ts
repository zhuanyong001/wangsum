export const createTypeData = (t: (key: string) => string): selectObj[] => [
  { name: t('type.recharge'), value: 1 }, // 管理员手动充值
  { name: t('type.withdraw'), value: 2 }, // 提现
  { name: t('type.exchange'), value: 3 }, // 兑换
  { name: t('type.reward'), value: 4 }, // 奖励
  { name: t('type.fee'), value: 5 }, // 手续费
  { name: t('type.stakeFreeze'), value: 6 }, // 质押冻结
  { name: t('type.stakeUnfreeze'), value: 7 }, // 存款解冻
  { name: t('type.stakeIncome'), value: 8 }, // 存款收益
  { name: t('type.withdrawFail'), value: 9 }, // 提现失败
  { name: t('type.adminRecharge'), value: 10 }, // 管理员手动充值
  { name: t('type.dpFreeze'), value: 11 }, // 存款冻结
  { name: t('type.dpUnfreeze'), value: 12 }, // 存款解冻
  { name: t('type.dpAward'), value: 13 }, // 存款发放奖励
  { name: t('type.loanPledge'), value: 14 }, // 贷款抵押
  { name: t('type.loanLoan'), value: 15 }, // 贷款借款
  { name: t('type.loanRepay'), value: 16 }, // 贷款还款本金
  { name: t('type.loanPledgeUnfreeze'), value: 17 }, // 贷款质押解冻
  { name: t('type.loanRepayInterest'), value: 18 }, // 贷款还款利息
  { name: t('type.air_drop'), value: 19 }, // 空投
]

// ['','充值','提现','兑换','奖励','手续费','质押冻结','质押解冻','质押解冻','质押收益','提现失败']
