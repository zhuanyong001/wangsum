<template>
  <div class="SunList">
    <div class="title">{{$t('index.index_1')}}</div>
    <div class="table">
      <table>
        <thead>
          <tr>
            <th v-for="column in columns" :key="column.key" :style="{ textAlign: column.align }">
              {{ column.title }}
            </th>
          </tr>
        </thead>
        <tbody v-if="user.assets && user.assets?.length > 0">
          <tr v-for="item in user.assets" :key="item.id" class="" @click="$router.push(`/FundDetails/${item.currency_id}`)">
            <td class="td-1">
              <div class="flex-row-start">
                <img class="img-fillet" :src="item.currency.icon" alt="" sizes="" srcset="" />
                <div class="name">{{ item.currency.name }}</div>
              </div>
            </td>
            <td class="td-2">
              <div class="name">{{ item.amount }}</div>
              <div class="allAmount">{{ multiplyAndFormat(item.amount, item.currency.price) }}</div>
            </td>
            <td style="padding-right: 0">
              <div class="td-3">
                <div  class="name">{{ item.pools_amount }}</div>
                <div class="allAmount">{{ multiplyAndFormat(item.pools_amount, item.currency.price) }}</div>
              </div>
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td colspan="3">
              <a-empty :description="null" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
  
<script setup lang="ts">
import { multiplyAndFormat } from '@/utils'
const userStore = useUser()
const user = computed(() => userStore.user)
const {t} = useI18n()
interface TableColumn {
  title: string
  dataIndex: string
  key: string
  align: 'left' | 'center' | 'right' // 仅允许这三种对齐方式
}

// 定义 columns 数组
const columns: TableColumn[] = [
  { title: t('index.Name_1'), dataIndex: 'name', key: 'name', align: 'left' },
  { title: t('index.Available_1'), dataIndex: 'age', key: 'age', align: 'center' },
  { title: t('index.Pledge_1'), dataIndex: 'address', key: 'address', align: 'right' },
]
</script>
  
<style lang="scss" scoped>
.SunList {
  @include box-box;

  .title {
    font-size: 1.42rem;
    color: var(--textColor);
    margin-bottom: 1.25rem;
    font-weight: 600;
  }

  .table {
    width: 100%;
    overflow-x: auto;

    table {
      width: 100%;
      border-collapse: collapse;

      thead {
        color: var(--textColor2);
      }

      th,
      td {
        padding: 1rem 0.83rem;
        border: none;
        color: var(--textColor);
        .name{
          font-weight: 600;
        }
      }

      th {
        color: var(--textColor2);
      }

      tbody tr {
        border-top: 1px solid var(--borderColor);
      }

      tbody tr:last-child {
        border-bottom: none;
      }

      tbody tr:hover {
        background: rgba(0, 0, 0, 0.05);
      }
      .td-1 {
        .name {
          margin-left: 0.58rem;
          font-weight: 600;
        }
      }
      .td-2{
        text-align: left;
      }
      .td-3{
        text-align: right;
      }
    }
    .allAmount {
      color: var(--textColor2);
      font-size: 1rem;
    }
    .empty {
      width: 100%;
      margin: 4rem auto;
    }
  }
}
</style>
  