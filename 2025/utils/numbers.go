package utils

func BuildRangeSlice(start, end int) []int {
	nums := make([]int, end-start)

	for i := start; i <= end; i++ {
		nums = append(nums, i)
	}

	return nums
}
