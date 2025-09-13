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

    <div>
        <div style="height: 500px">
            <canvas id="myChart"></canvas>
        </div>

        <div style="height: 500px">
            <canvas id="mySecondChart">
        </div>
    </div>



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
                    text: 'Monthly Category Top Sales'
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
