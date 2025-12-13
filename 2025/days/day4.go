package days

import (
	"os"

	"github.com/jkniest/advent-of-code/2025/utils"
)

type day4_coords struct {
	xx, yy int
}

type day4_item struct {
	x, y   int
	isMine bool
}

func day4_makeId(x, y, l int) int {
	return y*l + x
}

func day4_1_checkSide(x, y, colLength, rows int, checked map[int]day4_item) int {
	if x < 0 || x >= colLength || y < 0 || y >= rows {
		return 0
	}

	if checked[day4_makeId(x, y, colLength)].isMine {
		return 1
	}

	return 0
}

func day4_1_checkSides(x, y, colLength, rows int, checked map[int]day4_item) int {
	coords := []day4_coords{
		{xx: x - 1, yy: y - 1},
		{xx: x, yy: y - 1},
		{xx: x + 1, yy: y - 1},
		{xx: x - 1, yy: y},
		{xx: x + 1, yy: y},
		{xx: x - 1, yy: y + 1},
		{xx: x, yy: y + 1},
		{xx: x + 1, yy: y + 1},
	}

	return utils.NewCollection(coords).Sum(func(coord day4_coords) int {
		return day4_1_checkSide(coord.xx, coord.yy, colLength, rows, checked)
	})
}

func solveDay4_1() int {
	file, _ := os.Open("../inputs/day4.txt")
	defer file.Close()

	lines := utils.ReadLines(file)
	lineCount := len(lines)
	length := len(lines[0])

	grid := utils.FlatMapKey(lines, func(line string, y int) []utils.FlatMapKeyRes[day4_item] {
		return utils.Map([]rune(line), func(c rune, x int) utils.FlatMapKeyRes[day4_item] {
			return utils.FlatMapKeyRes[day4_item]{
				Key:  day4_makeId(x, y, length),
				Item: day4_item{x: x, y: y, isMine: c == '@'},
			}
		})
	})

	return utils.Sum(utils.MapToSlice[day4_item](grid), func(item day4_item) int {
		if !item.isMine {
			return 0
		}

		if day4_1_checkSides(item.x, item.y, length, lineCount, grid) < 4 {
			return 1
		}

		return 0
	})
}
