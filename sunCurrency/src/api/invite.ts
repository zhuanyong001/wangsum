import request from '@/utils/request'

// 邀请统计
export function InvitationStatistics() {
  return request({
    url: `/web3/get_team_stat`,
    method: 'get',
  })
}

// 邀请记录
export function InvitationRecodes(data) {
  return request({
    url: `/web3/get_team`,
    method: 'post',
    data,
  })
}

// 邀请佣金记录
export function InvitationPrice(data) {
  return request({
    url: `/web3/get_team_award_logs`,
    method: 'post',
    data,
  })
}

// 获取邀请列表
function getInviteList() {
  return request({
    url: '/web3/get_membership_list',
    method: 'get',
  })
}

export const getInvitesELECTList = () => {
  const InviteList = ref([])
  const { t } = useI18n()
  getInviteList().then((res) => {
    InviteList.value = res.data.map((item) => {
      return {
        name: t(item.name),
        value: item.id,
      }
    })
  })
  return { InviteList }
}
//获取节点成员数据

export const getNodeMemberStat = (data) => {
  return request({
    url: '/web3/get_node_member_stat',
    method: 'post',
    data,
  })
}
