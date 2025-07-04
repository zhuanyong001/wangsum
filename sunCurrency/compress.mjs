import fs from 'fs'
import path from 'path'
import archiver from 'archiver'
// import moment from 'moment'

// 定义要压缩的目录和输出文件的路径
const rootDirectory = process.cwd()
const targetDirectory = path.join(rootDirectory, 'dist')
// const formattedDate = moment().format('YYYY-MM-DD-HH-mm-ss')
const outputFilePath = path.join(rootDirectory, `dist.zip`)

// 创建输出流
const output = fs.createWriteStream(outputFilePath)
const archive = archiver('zip', {
  zlib: { level: 9 }, // 设置压缩级别
})

// 监听所有 archive 数据已写入文件时的事件
output.on('close', function () {
  console.log(`${archive.pointer()} total bytes`)
  console.log('Archiver has been finalized and the output file descriptor has closed.')
})

// 监听并捕获错误事件
archive.on('error', function (err) {
  throw err
})

// 将压缩包的数据管道写入文件
archive.pipe(output)

// 压缩目标目录中的所有文件和子目录
archive.directory(targetDirectory, false)

// 完成文件归档
archive.finalize()
