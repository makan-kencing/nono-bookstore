<?php

declare(strict_types=1);

use App\DTO\Response\CategorySalesDTO;
use App\DTO\Response\MonthlySalesDTO;

assert(isset($monthlySales) && is_array($monthlySales));
assert(isset($categorySales) && is_array($categorySales));
/**
 * @var MonthlySalesDTO[] $monthlySales
 * @var CategorySalesDTO[] $categorySales
 */

$title = 'Dashboard';

ob_start();
?>

    <style>
        /* 外层布局限制宽度，水平居中 */
        .analytics-panel{
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 12px;
        }

        /* 半透明玻璃卡片 */
        .glass-card{
            position: relative;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.20);
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,.24);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 20px;
        }

        /* 顶部半透明“突起” */
        .glass-card.with-notch::before{
            content:"";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 140px;
            height: 24px;
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.25);
            box-shadow: 0 2px 6px rgba(0,0,0,.2);
        }

        /* 单个图表块（保持原来 ~500px 高度） */
        .chart-block{
            height: 500px;
            padding: 8px;
        }
        .chart-block canvas{
            width: 100% !important;
            height: 100% !important;
        }

        /* 中间分隔器（发光横线 + 徽章） */
        .splitter{
            position: relative;
            margin: 10px 0 6px 0;
            height: 36px;
        }
        .splitter::before{
            content:"";
            position: absolute;
            top: 50%;
            left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
        }
        .splitter-badge{
            position: absolute;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            padding: 6px 12px;
            font-size: 12px;
            letter-spacing: .04em;
            color: #eaeaea;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 999px;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            white-space: nowrap;
            user-select: none;
        }
    </style>

    <section class="analytics-panel">
        <div class="glass-card with-notch">
            <div class="chart-block">
                <canvas id="myChart"></canvas>
            </div>

            <div class="splitter">
                <span class="splitter-badge">Overview Split</span>
            </div>

            <div class="chart-block">
                <canvas id="mySecondChart"></canvas>
            </div>
        </div>
    </section>



<script>
    const ctx = document.getElementById('myChart');

    const mixedChart = new Chart(ctx, {
        data: {
            datasets: [{
                type: 'bar',
                label: 'Total Revenue',
                data: [<?= implode(', ', array_map(
                    fn(MonthlySalesDTO $dto) => $dto->revenue / 100,
                    $monthlySales
                )) ?>],
                yAxisID: 'y'
            }, {
                type: 'line',
                label: 'Quantity Sold',
                data: [<?= implode(', ', array_map(
                    fn(MonthlySalesDTO $dto) => $dto->quantity,
                    $monthlySales
                )) ?>],
                yAxisID: 'y1'
            }],
            labels: [<?= implode(', ', array_map(
                fn(MonthlySalesDTO $dto) => '\'' . $dto->yearMonth . '\'',
                $monthlySales
            )) ?>]
        },
        options: {
            stacked: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Sales'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left'
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right'
                }
            }
        }
    })

    const ctx2 = document.getElementById('mySecondChart');

    const mixedSecondChart = new Chart(ctx2, {
        data: {
            datasets: [{
                type: 'bar',
                label: 'Total Revenue',
                data: [<?= implode(', ', array_map(
                    fn(CategorySalesDTO $dto) => $dto->quantity / 100,
                    $categorySales
                )) ?>],
                yAxisID: 'y'
            }, {
                type: 'line',
                label: 'Quantity Sold',
                data: [<?= implode(', ', array_map(
                    fn(CategorySalesDTO $dto) => $dto->revenue,
                    $categorySales
                )) ?>],
                yAxisID: 'y1'
            }],
            labels: [<?= implode(', ', array_map(
                fn(CategorySalesDTO $dto) => '\'' . $dto->categoryName . '\'',
                $categorySales
            )) ?>]
        },
        options: {
            stacked: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Category Top Sales'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left'
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right'
                }
            }
        }
    })




</script>



<?php
$content = ob_get_clean();

include __DIR__ . "/_base.php";
