import SunRechange from './src/SunRechange.vue'
import PledgeMining from './src/PledgeMining.vue'
import Sunmodal from '@/components/SunModal/index.vue'
import shareCodeShow from './src/shareCodeShow.vue'
import depositPopup from './src/depositPopup.vue'
import loanPopup from './src/loanPopup.vue'
import { showModal } from './modalUtils'
import loanShowPopup from './src/loanShowPopup.vue'
import NoticePopup from './src/NoticePopup.vue'

export function showLoanShow(fromParams: Pool, handleOk: Function) {
  // @ts-ignore
  showModal(loanShowPopup, fromParams, handleOk)
}

export function showLoan(fromParams: Pool, handleOk: Function) {
  // @ts-ignore
  showModal(loanPopup, fromParams, handleOk)
}
export function showDeposit(fromParams: Pool, handleOk: Function) {
  // @ts-ignore
  showModal(depositPopup, fromParams, handleOk)
}
export function showRechange(msg: { title: string; max?: string }, handleOk: Function, handleCancel: () => void) {
  // @ts-ignore
  showModal(SunRechange, { ...msg }, handleOk, handleCancel)
}

export function showPledgeMining(fromParams: Pool, handleOk: Function) {
  // @ts-ignore
  showModal(PledgeMining, fromParams, handleOk)
}

export function showModalConfirm(fromParams: any, handleOk) {
  // @ts-ignore
  showModal(Sunmodal, fromParams, handleOk)
}

export function showShareCode(msg: { title: string }, handleOk, handleCancel: () => void) {
  // @ts-ignore
  showModal(shareCodeShow, { title: msg.title }, handleOk, handleCancel)
}

export function showNoticePopup(msg: { title: string; content: string }, handleOk: Function, handleCancel: () => void) {
  // @ts-ignore
  showModal(NoticePopup, msg, handleOk, handleCancel)
}
