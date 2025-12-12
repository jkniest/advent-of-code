package days

import (
	"testing"
)

func TestDay2(t *testing.T) {
	t.Run("part 1", func(t *testing.T) {
		solution := solveDay2_1()

		t.Logf("Result = %d", solution)
	})

	t.Run("part 2", func(t *testing.T) {
		solution := solveDay2_2()

		t.Logf("Result = %d", solution)
	})

}
