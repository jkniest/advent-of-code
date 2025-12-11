package utils

import (
	"bufio"
	"io"
)

func ReadLines(r io.Reader) []string {
	var lines []string
	scanner := bufio.NewScanner(r)
	for scanner.Scan() {
		lines = append(lines, scanner.Text())
	}

	return lines
}
