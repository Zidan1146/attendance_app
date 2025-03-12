import './bootstrap';
import Chart from 'chart.js/auto';

// (async function() {
//   const data = [
//     { month: "January", clockIn: 120, late: 30, quitEarly: 40, absent: 28 },
//     { month: "February", clockIn: 115, late: 35, quitEarly: 42, absent: 28 },
//     { month: "March", clockIn: 125, late: 25, quitEarly: 38, absent: 28 },
//     { month: "April", clockIn: 130, late: 20, quitEarly: 36, absent: 28 },
//     { month: "May", clockIn: 118, late: 32, quitEarly: 39, absent: 28 },
//     { month: "June", clockIn: 122, late: 28, quitEarly: 41, absent: 28 },
//     { month: "July", clockIn: 128, late: 22, quitEarly: 37, absent: 28 },
//     { month: "August", clockIn: 121, late: 29, quitEarly: 38, absent: 28 },
//     { month: "September", clockIn: 119, late: 31, quitEarly: 40, absent: 28 },
//     { month: "October", clockIn: 124, late: 26, quitEarly: 39, absent: 28 },
//     { month: "November", clockIn: 127, late: 23, quitEarly: 37, absent: 28 },
//     { month: "December", clockIn: 123, late: 27, quitEarly: 38, absent: 28 },
//   ];

//   new Chart(
//     document.getElementById('attendanceChart'),
//     {
//       type: 'bar',
//       data: {
//         labels: data.map(row => row.month),
//         datasets: [
//           {
//             label: 'Clock in',
//             data: data.map(row => row.clockIn)
//           },
//           {
//             label: 'Late',
//             data: data.map(row => row.late)
//           },
//           {
//             label: 'Quit early',
//             data: data.map(row => row.quitEarly)
//           },
//           {
//             label: 'Absent',
//             data: data.map(row => row.absent)
//           }
//         ]
//       },
//       options: {
//         scales: {
//             x: {
//                 stacked: true
//             },
//             y: {
//                 stacked: true
//             }
//         }
//       }
//     }
//   );
// })();
