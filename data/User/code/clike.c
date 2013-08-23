/*
 * =====================================================================================
 *
 *       Filename:  merger_sort.c
 *
 *    Description:  123
 *
 *        Version:  1.0
 *        Created:  08/17/2013 02:19:38 PM
 *       Revision:  none
 *       Compiler:  gcc
 *
 *         Author:  YOUR NAME (), 
 *   Organization:  
 *
 * =====================================================================================
 */

#include <stdlib.h>
#include <stdio.h>

void dis_array(char *tips, int a[], int left, int right)
{
		int i = 0;

		printf("----%s---------n", tips);
		for (i = left; i <= right; i++) {
				printf("%d ", a[i]);
		}

		printf("n");

		return;
}


void merge(int array[], int temp[], int left, int mid, int right)
{
		int i = left;
		int j = mid;
		int k = left;

		while (i < mid && j <= right) {
				if (array[i] <= array[j]) {
						temp[k++] = array[i++];
				} else {
						temp[k++] = array[j++];
				}	
		}

		while (i < mid) {
				temp[k++] = array[i++];
		}

		while (j <= right) {
				temp[k++] = array[j++];
		}

		k = left;
		while (k <= right) {
				array[k] = temp[k];
				k++;
		}

		return;
}

void merge_sort_ass(int array[], int temp[], int left, int right)
{
		int mid = 0;

		if (left >= right)
				return;

		mid = (right - left) / 2 + left;

		merge_sort_ass(array, temp, left, mid);
		merge_sort_ass(array, temp, mid+1, right);
		merge(array, temp, left, mid+1, right);

		return; 
}


void merge_sort(int array[], int left, int right)
{
		int len = 0;
		int *temp = NULL;

		len = right - left + 1;


		temp = (int *)malloc(sizeof(int) * len);
		if (temp == NULL) {
				exit(1);
		}

		merge_sort_ass(array, temp, left, right);

		free(temp);

		return;
}


int main()
{
		int left, right;
		int array[] = {101, 100, 34, 3, 345, 23, 342, 22453, 55};

		left = 0;
		right = sizeof(array) / sizeof(array[0]) - 1;

		dis_array("sort before", array, left, right);
		printf("nnn");
		merge_sort(array, left, right);
		dis_array("after sorted", array, left, right);
		printf("nnn");

		return 0;
}

