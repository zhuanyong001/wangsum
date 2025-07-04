import { 
  startOfToday, 
  endOfToday, 
  startOfYesterday, 
  endOfYesterday,
  startOfWeek,
  endOfWeek,
  startOfMonth,
  endOfMonth,
  startOfQuarter,
  endOfQuarter,
  startOfYear,
  endOfYear,
  subWeeks,
  subMonths,
  subQuarters,
  format
} from 'date-fns';

const formatDateRange = (start: Date, end: Date): [string, string] => [
  format(start, 'yyyy-MM-dd HH:mm:ss'),
  format(end, 'yyyy-MM-dd HH:mm:ss')
];

const useDateRanges = () => {
  const getTodayRange = () => formatDateRange(startOfToday(), endOfToday());
  const getYesterdayRange = () => formatDateRange(startOfYesterday(), endOfYesterday());
  const getThisWeekRange = () => formatDateRange(startOfWeek(new Date(), { weekStartsOn: 1 }), endOfWeek(new Date(), { weekStartsOn: 1 }));
  const getLastWeekRange = () => {
    const lastWeek = subWeeks(new Date(), 1);
    return formatDateRange(startOfWeek(lastWeek, { weekStartsOn: 1 }), endOfWeek(lastWeek, { weekStartsOn: 1 }));
  };
  const getThisMonthRange = () => formatDateRange(startOfMonth(new Date()), endOfMonth(new Date()));
  const getLastMonthRange = () => {
    const lastMonth = subMonths(new Date(), 1);
    return formatDateRange(startOfMonth(lastMonth), endOfMonth(lastMonth));
  };
  const getThisQuarterRange = () => formatDateRange(startOfQuarter(new Date()), endOfQuarter(new Date()));
  const getLastQuarterRange = () => {
    const lastQuarter = subQuarters(new Date(), 1);
    return formatDateRange(startOfQuarter(lastQuarter), endOfQuarter(lastQuarter));
  };
  const getThisYearRange = () => formatDateRange(startOfYear(new Date()), endOfYear(new Date()));

  return {
    getTodayRange,
    getYesterdayRange,
    getThisWeekRange,
    getLastWeekRange,
    getThisMonthRange,
    getLastMonthRange,
    getThisQuarterRange,
    getLastQuarterRange,
    getThisYearRange
  };
};

const dateRanges = useDateRanges();

export const createTimeData = (t: (key: string) => string) => [
  {
    name: t('time.today'),
    value: dateRanges.getTodayRange
  },
  {
    name: t('time.yesterday'),
    value: dateRanges.getYesterdayRange
  },
  {
    name: t('time.thisWeek'),
    value: dateRanges.getThisWeekRange
  },
  {
    name: t('time.lastWeek'),
    value: dateRanges.getLastWeekRange
  },
  {
    name: t('time.thisMonth'),
    value: dateRanges.getThisMonthRange
  },
  {
    name: t('time.lastMonth'),
    value: dateRanges.getLastMonthRange
  },
  {
    name: t('time.thisQuarter'),
    value: dateRanges.getThisQuarterRange
  },
  {
    name: t('time.lastQuarter'),
    value: dateRanges.getLastQuarterRange
  },
  {
    name: t('time.thisYear'),
    value: dateRanges.getThisYearRange
  }
];
